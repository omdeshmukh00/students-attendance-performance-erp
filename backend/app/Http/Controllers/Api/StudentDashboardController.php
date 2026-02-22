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

        /*
        ====================================
        NORMALIZE PERCENTAGE (CRITICAL FIX)
        ====================================
        */

        $overallPercent = ceil($student->overall_percentage);

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

        return response()->json([

            'student_id' => $student->student_id,
            'student_name' => $student->name,
            'semester' => $student->semester,
            'branch' => $student->branch,
            'section' => $student->section,

            'overall' => [
                'total' => $student->overall_total,
                'attended' => $student->overall_attended,
                'percentage' => $overallPercent
            ],

            'eligibility' => [
                'detained' => $detained,
                'mse1' => $mse1Eligible,
                'mse2' => $mse2Eligible,
                'exam_form' => $examFormEligible,
                'incentive' => $incentiveEligible
            ],

            'subjects' => $student->attendances->map(function ($att) {

                $percentage = $att->total > 0
                    ? ceil(($att->attended / $att->total) * 100)
                    : 0;

                return [
                    'subject_code' => $att->subject->subject_code,
                    'subject_name' => $att->subject->subject_name,
                    'faculty' => $att->subject->faculty,
                    'attended' => $att->attended,
                    'total' => $att->total,
                    'percentage' => min($percentage, 100)
                ];

            })->values()

        ]);
    }
}