<?php

namespace App\Services;

class AttendanceEligibilityService
{

    public static function calculate($attended,$total)
    {
        if($total == 0){
            return [
                'percentage'=>0,
                'mse1'=>false,
                'mse2'=>false,
                'endsem'=>false,
                'incentive'=>false
            ];
        }

        $percentage = ceil(($attended/$total)*100);

        return [

            'percentage'=>$percentage,

            'mse1'=>$percentage >= 65,

            'mse2'=>$percentage >= 65,

            'endsem'=>$percentage >= 65,

            'incentive'=>$percentage >= 75
        ];
    }

}