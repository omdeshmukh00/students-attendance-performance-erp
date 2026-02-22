<?php

namespace App\Services;

use App\Models\AttendanceEligibility;

class DefaulterService
{
    public static function getDefaulters($limit = 65)
    {
        return AttendanceEligibility::with(['student', 'subject'])
            ->where('attendance_percentage', '<', $limit)
            ->orderBy('attendance_percentage', 'asc')
            ->get();
    }
}