<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceEligibility extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'attendance_percentage',
        'eligible_mse1',
        'eligible_mse2',
        'eligible_endsem',
        'eligible_incentive'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}