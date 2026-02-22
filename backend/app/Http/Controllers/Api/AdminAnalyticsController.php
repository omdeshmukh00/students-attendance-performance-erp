<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

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

            $percent = ceil($student->overall_percentage);

            if ($percent >= 65) {
                $eligible++;
            }

            if ($percent < 65) {
                $defaulters++;
            }
        }

        $average = ceil($students->avg('overall_percentage'));

        return response()->json([
            'total_students' => $totalStudents,
            'eligible_students' => $eligible,
            'defaulters' => $defaulters,
            'average_attendance' => $average
        ]);
    }

    /*
    =================================
    DEFAULTER LIST
    =================================
    */

    public function defaulters()
    {
        $students = Student::all();

        $list = $students->filter(function ($student) {

            $percent = ceil($student->overall_percentage);

            return $percent < 65;
        })
        ->values()
        ->map(function ($student) {

            return [
                'student_id' => $student->student_id,
                'name' => $student->name,
                'branch' => $student->branch,
                'section' => $student->section,
                'semester' => $student->semester,
                'attendance' => ceil($student->overall_percentage)
            ];
        });

        return response()->json($list);
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
                return ceil($s->overall_percentage) < 65;
            })->count();

            $avg = ceil($group->avg('overall_percentage'));

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
        $students = Student::with('attendances.subject')->get();

        $subjects = [];

        foreach ($students as $student) {

            foreach ($student->attendances as $att) {

                $code = $att->subject->subject_code;

                if (!isset($subjects[$code])) {

                    $subjects[$code] = [
                        'subject_code' => $code,
                        'subject_name' => $att->subject->subject_name,
                        'total_students' => 0,
                        'total_percent' => 0
                    ];
                }

                $percent = $att->total > 0
                    ? ceil(($att->attended / $att->total) * 100)
                    : 0;

                $subjects[$code]['total_students']++;
                $subjects[$code]['total_percent'] += $percent;
            }
        }

        $result = [];

        foreach ($subjects as $sub) {

            $avg = ceil($sub['total_percent'] / $sub['total_students']);

            $result[] = [
                'subject_code' => $sub['subject_code'],
                'subject_name' => $sub['subject_name'],
                'average_attendance' => $avg,
                'students' => $sub['total_students']
            ];
        }

        return response()->json($result);
    }
}