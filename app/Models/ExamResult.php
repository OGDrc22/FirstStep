<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'student_id',
        'score',
        'predicted_track',
        'track_percentage',
        'accuracy',
        'accuracy_per_category',
        'duration_per_category',
        'answers',
        'questionsData',
        'questions'
    ];

    protected $casts = [
        'track_percentage' => 'array',
        'answers' => 'array',
        'questions' => 'array',
        'accuracy_per_category' => 'array',
        'duration_per_category' => 'array',
        'questionsData' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(student_tb::class, 'student_id');
    }
}
