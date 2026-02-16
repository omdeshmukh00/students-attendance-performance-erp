<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
    'student_id',
    'subject_id',
    'attended',
    'total'
];

public function student()
{
    return $this->belongsTo(Student::class);
}

public function subject()
{
    return $this->belongsTo(Subject::class);
}

}
