<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{



public function login(Request $request)
{
    if (!$request->has('student_id')) {
        return response()->json([
            "message" => "Student ID required"
        ], 400);
    }

    $studentId = strtoupper($request->student_id);

    $student = Student::where('student_id', $studentId)->first();

    if (!$student) {
        return response()->json([
            "message" => "Invalid credentials"
        ], 401);
    }

    $request->session()->regenerate();

    session(['student_id' => $studentId]);

    return response()->json([
        "message" => "Login successful"
    ]);
}


    public function logout(Request $request)
    {
        $request->session()->forget('student_id');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            "message" => "Logged out"
        ]);
    }

}