<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentDashboardController extends Controller
{

    public function show($bt_id)
    {
        /* ===== NORMALIZE BTID (VERY IMPORTANT) ===== */
        $bt_id = strtoupper(trim($bt_id));

        $student = Student::where('student_id', $bt_id)
            ->with(['attendances.subject'])
            ->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'student_id' => $student->student_id,
            'student_name' => $student->name,
            'semester' => $student->semester,
            'branch' => $student->branch,
            'section' => $student->section,

            'overall' => [
                'total' => $student->overall_total,
                'attended' => $student->overall_attended,
                'percentage' => min(round($student->overall_percentage, 2), 100)
            ],

            'subjects' => $student->attendances->map(function ($att) {

                $percentage = $att->total > 0
                    ? round(($att->attended / $att->total) * 100, 2)
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
