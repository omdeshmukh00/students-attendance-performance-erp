<?php

namespace App\Jobs;

use App\Models\ImportLog;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\AttendanceEligibility;
use App\Services\AttendanceEligibilityService;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCsvImport implements ShouldQueue
{
    use Dispatchable, Queueable;

    private $file;
    private $hash;

    public function __construct($file, $hash)
    {
        $this->file = $file;
        $this->hash = $hash;
    }

    public function handle()
    {
        $path = $this->file;

        if (!file_exists($path)) {
            return;
        }

        $rows = array_map('str_getcsv', file($path));

        $processed = 0;

        DB::transaction(function () use ($rows, &$processed) {

            foreach ($rows as $index => $row) {

                // Skip header row
                $studentId = trim($row[0] ?? '');

                if (!preg_match('/^BT/i', $studentId)) {
                    continue;
                }

                $studentName = trim($row[1] ?? '');
                $subjectCode = trim($row[2] ?? '');
                $attended    = (int) ($row[3] ?? 0);
                $total       = (int) ($row[4] ?? 0);

                if (!$studentId || !$subjectCode) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Student
                |--------------------------------------------------------------------------
                */

                $student = Student::firstOrCreate(
                    ['student_id' => $studentId],
                    ['name' => $studentName]
                );

                /*
                |--------------------------------------------------------------------------
                | Subject
                |--------------------------------------------------------------------------
                */

                $subject = Subject::firstOrCreate(
                    ['subject_code' => $subjectCode]
                );

                /*
                |--------------------------------------------------------------------------
                | Attendance
                |--------------------------------------------------------------------------
                */

                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id
                    ],
                    [
                        'attended' => $attended,
                        'total' => $total
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Eligibility Calculation
                |--------------------------------------------------------------------------
                */

                $calc = AttendanceEligibilityService::calculate($attended, $total);

                if ($calc) {

                    AttendanceEligibility::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'subject_id' => $subject->id
                        ],
                        [
                            'attendance_percentage' => $calc['percentage'],
                            'eligible_mse1' => $calc['mse1'],
                            'eligible_mse2' => $calc['mse2'],
                            'eligible_endsem' => $calc['endsem'],
                            'eligible_incentive' => $calc['incentive']
                        ]
                    );
                }

                $processed++;
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Import Log
        |--------------------------------------------------------------------------
        */

        ImportLog::create([
            'file_name' => basename($this->file),
            'file_hash' => $this->hash,
            'rows_processed' => $processed
        ]);
    }
}