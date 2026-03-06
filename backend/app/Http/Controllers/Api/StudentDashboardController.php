<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentDashboardController extends Controller
{
    public function show($bt_id)
    {
        /* ===== NORMALIZE BTID ===== */
        $bt_id = strtoupper(trim($bt_id));

        $student = Student::where('student_id', $bt_id)
            ->with(['attendances.subject'])
            ->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student not found'
            ], 404);
        }

        $subjectAttendances = $student->attendances;

        /*
        ====================================
        NORMALIZE SUBJECT ATTENDANCE
        ====================================
        */

        $normalizedSubjects = $subjectAttendances->map(function ($att, $index) {

            $percentage = $att->total > 0
                ? ($att->attended / $att->total) * 100
                : 0;

            return [
                'model' => $att,
                'attended' => $att->attended,
                'total' => $att->total,
                'percentage' => $percentage,
                'index' => $index
            ];

        })->values()->toArray();

        /*
        ====================================
        NORMALIZE OVERALL PERCENT
        ====================================
        */

        $calculatedOverallAttended = 0;
        $calculatedOverallTotal = 0;

        foreach ($normalizedSubjects as $subjectItem) {
            // Unenrolled electives have 0 attendance, so we skip them for the overall calculation
            if ($subjectItem['attended'] > 0) {
                $calculatedOverallAttended += $subjectItem['attended'];
                $calculatedOverallTotal += $subjectItem['total'];
            }
        }

        $overallPercentRaw = $calculatedOverallTotal > 0
            ? ($calculatedOverallAttended / $calculatedOverallTotal) * 100
            : 0;

        $overallPercent = min(100, ceil($overallPercentRaw));

        /*
        ====================================
        ELIGIBILITY LOGIC
        ====================================
        */

        $mse1Eligible = $overallPercent >= 55;
        $mse2Eligible = $overallPercent >= 65;

        $examFormEligible = $overallPercent >= 65;

        $detained = $overallPercent < 55;

        $incentiveEligible = $overallPercent >= 75;

        /*
        ====================================
        BUILD SUBJECT RESPONSE
        ====================================
        */

        $subjects = collect($normalizedSubjects)->map(function ($item) {

            $att = $item['model'];

            $attended = $item['attended'];
            $total = $item['total'];

            $percentage = $total > 0
                ? ceil(($attended / $total) * 100)
                : 0;

            return [
                'subject_code' => $att->subject->subject_code,
                'subject_name' => $att->subject->subject_name,
                'faculty' => $att->subject->faculty,
                'attended' => min($attended, $total),
                'total' => $total,
                'percentage' => min($percentage, 100)
            ];

        })->values();

        /*
        ====================================
        FINAL RESPONSE
        ====================================
        */

        return response()->json([

            'student_id' => $student->student_id,
            'student_name' => $student->name,
            'semester' => $student->semester,
            'branch' => $student->branch,
            'section' => $student->section,

            'overall' => [
                'total' => $calculatedOverallTotal,
                'attended' => $calculatedOverallAttended,
                'percentage' => $overallPercent
            ],

            'eligibility' => [
                'detained' => $detained,
                'mse1' => $mse1Eligible,
                'mse2' => $mse2Eligible,
                'exam_form' => $examFormEligible,
                'incentive' => $incentiveEligible
            ],

            'subjects' => $subjects

        ]);
    }
}