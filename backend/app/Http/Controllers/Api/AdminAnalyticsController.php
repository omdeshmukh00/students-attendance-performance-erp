<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;

class AdminAnalyticsController extends Controller
{

    /*
    =================================
    COLLEGE OVERVIEW
    =================================
    */

    public function collegeOverview()
    {
        $students = Student::all();

        $totalStudents = $students->count();

        $eligible = 0;
        $defaulters = 0;

        foreach ($students as $student) {

            $percent = $student->overall_total > 0
                ? ceil(($student->overall_attended / $student->overall_total) * 100)
                : 0;

            if ($percent >= 65) {
                $eligible++;
            } else {
                $defaulters++;
            }
        }

        $average = ceil(
            $students->map(function ($s) {
                return $s->overall_total > 0
                    ? ($s->overall_attended / $s->overall_total) * 100
                    : 0;
            })->avg()
        );

        return response()->json([
            'total_students' => $totalStudents,
            'eligible_students' => $eligible,
            'defaulters' => $defaulters,
            'average_attendance' => $average
        ]);
    }

    /*
    =================================
    DEFAULTERS LIST
    =================================
    */

    public function defaulters()
    {
        $students = Student::all();

        $data = $students
            ->map(function ($s) {

                $percent = $s->overall_total > 0
                    ? ceil(($s->overall_attended / $s->overall_total) * 100)
                    : 0;

                return [
                    'bt_id' => $s->student_id,
                    'name' => $s->name,
                    'percentage' => $percent
                ];
            })
            ->filter(fn($s) => $s['percentage'] < 65)
            ->values();

        return response()->json($data);
    }

    /*
    =================================
    BRANCH ANALYTICS
    =================================
    */

    public function branchStats()
    {
        $students = Student::all();

        $branches = $students->groupBy('branch');

        $data = [];

        foreach ($branches as $branch => $group) {

            $total = $group->count();

            $defaulters = $group->filter(function ($s) {

                $percent = $s->overall_total > 0
                    ? ceil(($s->overall_attended / $s->overall_total) * 100)
                    : 0;

                return $percent < 65;
            })->count();

            $avg = ceil(
                $group->map(function ($s) {
                    return $s->overall_total > 0
                        ? ($s->overall_attended / $s->overall_total) * 100
                        : 0;
                })->avg()
            );

            $data[] = [
                'branch' => $branch,
                'students' => $total,
                'defaulters' => $defaulters,
                'average_attendance' => $avg
            ];
        }

        return response()->json($data);
    }

    /*
    =================================
    SUBJECT ANALYTICS
    =================================
    */

    public function subjectStats()
    {
        $subjects = Subject::with('attendances')->get();

        $data = $subjects->map(function ($subject) {

            $att = $subject->attendances;

            $totalAttended = $att->sum('attended');
            $totalLectures = $att->sum('total');

            $percentage = $totalLectures > 0
                ? ceil(($totalAttended / $totalLectures) * 100)
                : 0;

            return [
                'subject_code' => $subject->subject_code,
                'subject_name' => $subject->subject_name,
                'faculty' => $subject->faculty,
                'percentage' => $percentage
            ];
        });

        return response()->json($data);
    }
}