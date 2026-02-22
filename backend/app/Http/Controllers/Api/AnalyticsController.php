<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $students = Student::count();

        $avgAttendance = Attendance::avg('percentage');

        $lowAttendance = Attendance::where('percentage','<',65)->count();

        $excellent = Attendance::where('percentage','>=',75)->count();

        return response()->json([
            'total_students' => $students,
            'average_attendance' => round($avgAttendance,2),
            'below_65' => $lowAttendance,
            'above_75' => $excellent
        ]);
    }
}