<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;

class StudentController extends Controller
{
    public function show($id)
    {
        $student = Student::where('student_id',$id)->first();

        if(!$student){
            return response()->json([
                "message"=>"Student not found"
            ],404);
        }

        $subjects = Attendance::with('subject')
            ->where('student_id',$student->id)
            ->get();

        $total = $subjects->sum('total');
        $attended = $subjects->sum('attended');

        return response()->json([
            "student_id"=>$student->student_id,
            "student_name"=>$student->name,
            "semester"=>$student->semester,
            "branch"=>$student->branch,
            "section"=>$student->section,

            "overall"=>[
                "total"=>$total,
                "attended"=>$attended,
                "percentage"=>$total ? ceil(($attended/$total)*100) : 0
            ],

            "subjects"=>$subjects->map(function($s){
                return [
                    "subject_name"=>$s->subject->subject_code." ".$s->subject->subject_name." (".$s->subject->faculty.")",
                    "attended"=>$s->attended,
                    "total"=>$s->total,
                    "percentage"=>$s->percentage
                ];
            })
        ]);
    }
}