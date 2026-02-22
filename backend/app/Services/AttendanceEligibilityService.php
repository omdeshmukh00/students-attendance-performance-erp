<?php

namespace App\Services;

class AttendanceEligibilityService
{
    public static function calculate($attended, $total)
    {
        if ($total == 0) {
            return null;
        }

        $percentage = ceil(($attended / $total) * 100);

        return [
            'percentage' => $percentage,
            'mse1' => $percentage >= 55,
            'mse2' => $percentage >= 65,
            'endsem' => $percentage >= 65,
            'incentive' => $percentage >= 75
        ];
    }
}