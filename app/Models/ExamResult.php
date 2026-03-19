<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'student_id',
        'score',
        'predicted_track',
        'secondary_track',
        'track_percentage',
        'core_competencies',
        'detailed_competencies',
        'evaluation_note',
        'accuracy',
        'accuracy_per_category',
        'duration_per_category',
        'answers',
        'questionsData',
        'questions'
    ];

    protected $casts = [
        'predicted_track' => 'array',
        'secondary_track' => 'array',
        'track_percentage' => 'array',
        'core_competencies' => 'array',
        'evaluation_note' => 'string',
        'detailed_competencies' => 'array',
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

    public function getSortedTracksAttribute()
    {
        // Returns the tracks sorted by percentage descending
        return collect($this->track_percentage)->sortByDesc('percentage');
    }
}
