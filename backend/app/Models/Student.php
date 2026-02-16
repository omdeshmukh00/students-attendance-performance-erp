<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;

class Student extends Model
{
protected $fillable = [
    'student_id',
    'name',
    'branch',
    'section',
    'semester',
    'overall_total',
    'overall_attended',
    'overall_percentage'
];



    public function attendances()
    {
        return $this->hasMany(Attendance::class);
        
    }
}
