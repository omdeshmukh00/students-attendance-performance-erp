<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'file_name',
        'file_hash',
        'rows_processed'
    ];
}