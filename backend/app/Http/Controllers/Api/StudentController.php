<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    // Get All Students
    public function index()
    {
        return response()->json(Student::all());
    }

    // Store Student
    public function store(Request $request)
    {
        $student = Student::create($request->all());
        return response()->json($student, 201);
    }

    // Get Single Student
    public function show($id)
    {
        return response()->json(Student::findOrFail($id));
    }
}
