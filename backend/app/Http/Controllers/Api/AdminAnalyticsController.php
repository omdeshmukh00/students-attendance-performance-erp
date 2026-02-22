<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{

    /* ======================================
       BRANCH ANALYTICS
    ====================================== */
    public function branchStats()
    {
        $data = Student::select(
                'branch',
                DB::raw('count(*) as total_students'),
                DB::raw('avg(overall_percentage) as avg_attendance')
            )
            ->groupBy('branch')
            ->orderBy('branch')
            ->get();

        return response()->json($data);
    }


    /* ======================================
       SUBJECT ANALYTICS
    ====================================== */
    public function subjectStats()
    {
        $data = Attendance::join('subjects','subjects.id','=','attendances.subject_id')
            ->select(
                'subjects.subject_code',
                'subjects.subject_name',
                DB::raw('avg(attendances.percentage) as avg_percentage'),
                DB::raw('count(attendances.student_id) as students')
            )
            ->groupBy('subjects.subject_code','subjects.subject_name')
            ->orderBy('subjects.subject_code')
            ->get();

        return response()->json($data);
    }


    /* ======================================
       DEFAULTERS
    ====================================== */
    public function defaulters()
    {
        $data = Student::where('overall_percentage','<',65)
            ->orderBy('overall_percentage')
            ->get([
                'student_id',
                'name',
                'branch',
                'section',
                'semester',
                'overall_percentage'
            ]);

        return response()->json($data);
    }
    public function collegeOverview()
{
    $students = Student::count();

    $avg = Student::avg('overall_percentage');

    $defaulters = Student::where('overall_percentage','<',65)->count();

    return response()->json([
        'total_students'=>$students,
        'average_attendance'=>round($avg,2),
        'defaulters'=>$defaulters
    ]);
}
}