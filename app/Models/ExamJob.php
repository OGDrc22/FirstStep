<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamJob extends Model
{
    protected $table = 'exam_jobs';

    protected $fillable = [
        'student_id',
        'payload',
        'output',
        'status',
        'message',
        'error_message',
        'progress',
        'rf_features'
    ];

    protected $casts = [
        'payload' => 'array',
        'output' => 'array',
        'rf_features' => 'array',
    ];
}
