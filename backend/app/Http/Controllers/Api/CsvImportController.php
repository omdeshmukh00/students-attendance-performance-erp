<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\Csv\Reader;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;

class CsvImportController extends Controller
{

    /* ================= CLEAN ================= */
    private function clean($text)
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $text ?? '')));
    }

    /* ================= METADATA ================= */
    private function extractMetaFromTopRows($rows)
    {
        $section = null;
        $branch = null;
        $semester = null;

        foreach (array_slice($rows, 0, 15) as $row) {

            $line = strtolower(trim(implode(' ', $row)));

            if (str_contains($line, 'section')) {
                if (preg_match('/section\s*[:\-]?\s*([a-z0-9\-]+)/i', $line, $m)) {

                    $section = strtoupper($m[1]);

                    if (str_contains($section, '-')) {
                        $branch = explode('-', $section)[0];
                    } else {
                        $branch = $section;
                    }
                }
            }

            if (str_contains($line, 'semester')) {
                if (preg_match('/semester\s*[:\-]?\s*(\d+)/i', $line, $m)) {
                    $semester = (int) $m[1];
                }
            }
        }

        return [
            'branch' => $branch ?? 'UNKNOWN',
            'section' => $section ?? 'UNKNOWN',
            'semester' => $semester ?? 0
        ];
    }

    /* ================= HEADER ================= */
    private function findHeaderIndex($rows)
    {
        foreach ($rows as $i => $row) {
            $line = $this->clean(implode(' ', $row));

            if (str_contains($line, 'roll') && str_contains($line, 'student')) {
                return $i;
            }
        }

        return null;
    }

    /* ================= SUBJECT PARSER ================= */
private function parseSubject($text)
{
    $text = trim(preg_replace('/\s+/', ' ', $text));

    if (preg_match('/^([A-Z0-9]+)\s+(.*?)\s*\((.*?)\)$/i', $text, $m)) {
        return [
            'code' => trim($m[1]),
            'name' => trim($m[2]),
            'faculty' => trim($m[3])
        ];
    }

    if (preg_match('/^([A-Z0-9]+)\s+(.*)$/i', $text, $m)) {
        return [
            'code' => trim($m[1]),
            'name' => trim($m[2]),
            'faculty' => null
        ];
    }

    return ['code'=>null,'name'=>null,'faculty'=>null];
}

public function import($filename)
{
    set_time_limit(0);

    $path = storage_path("app/csv/".$filename);

    if (!file_exists($path)) {
        return response()->json(['error'=>'File not found'],404);
    }

    $csv = Reader::createFromPath($path, 'r');

    $rows = [];
    foreach ($csv->getRecords() as $r) {
        $rows[] = array_values($r);
    }

    $meta = $this->extractMetaFromTopRows($rows);

    $headerIndex = $this->findHeaderIndex($rows);
    if ($headerIndex === null) {
        return response()->json(['error'=>'Header not found'],500);
    }

    $headers = $rows[$headerIndex];

    // LOCK LAST 2 COLUMNS
    $totalCol = count($headers) - 2;
    $percentCol = count($headers) - 1;

    // TOTAL LECTURES ROW
    $totalLecturesRow = $rows[$headerIndex + 1] ?? [];

    $overallTotalFromSheet =
        isset($totalLecturesRow[$totalCol]) &&
        is_numeric($totalLecturesRow[$totalCol])
            ? (int)$totalLecturesRow[$totalCol]
            : 0;

    $imported = 0;

    /* ===== STUDENT LOOP ===== */
    for ($i = $headerIndex + 2; $i < count($rows); $i++) {

        $row = $rows[$i];
        if (!array_filter($row)) continue;

        if (count($row) < count($headers)) {
            $row = array_pad($row, count($headers), null);
        }

        if (count($row) > count($headers)) {
            $row = array_slice($row, 0, count($headers));
        }

        $record = array_combine($headers, $row);

        $roll = trim($record['Roll Number'] ?? '');
        $name = trim($record['Student Name'] ?? '');

        if (!$roll) continue;

        /* ===== OVERALL ===== */
        $attendedRaw = $row[$totalCol] ?? null;
        $percentRaw = $row[$percentCol] ?? null;

        $overallAttended = is_numeric($attendedRaw) ? (int)$attendedRaw : 0;
        $overallPercent = is_numeric($percentRaw) ? (float)$percentRaw : 0;
        $overallTotal = $overallTotalFromSheet;

        $student = Student::updateOrCreate(
            ['student_id'=>$roll],
            [
                'name'=>$name,
                'branch'=>$meta['branch'],
                'section'=>$meta['section'],
                'semester'=>$meta['semester'],
                'overall_total'=>$overallTotal,
                'overall_attended'=>$overallAttended,
                'overall_percentage'=>$overallPercent
            ]
        );

        /* ===== SUBJECT + ATTENDANCE IMPORT ===== */
        foreach ($record as $column=>$value) {

            if ($value === '' || $value === null) continue;

            if (in_array($column, ['#','Roll Number','Student Name'])) continue;

            if (str_contains(strtolower($column), 'total')) continue;
            if (str_contains(strtolower($column), 'percent')) continue;
            if (str_contains(strtolower($column), 'additional')) continue;

            $parsed = $this->parseSubject($column);
            if (!$parsed['code']) continue;

            $subject = Subject::updateOrCreate(
                ['subject_code'=>$parsed['code']],
                [
                    'subject_name'=>$parsed['name'],
                    'faculty'=>$parsed['faculty']
                ]
            );

            $attended = (int)$value;
            $total = 15; // temporary fallback

            Attendance::updateOrCreate(
                [
                    'student_id'=>$student->id,
                    'subject_id'=>$subject->id
                ],
                [
                    'attended'=>$attended,
                    'total'=>$total,
                    'percentage'=>$total>0 ? ($attended/$total)*100 : 0
                ]
            );
        }

        $imported++;
    }

    return response()->json([
        'message'=>'IMPORT SUCCESS',
        'students_imported'=>$imported,
        'overall_total_detected'=>$overallTotalFromSheet
    ]);
}


}
