<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = ['student_id', 'job_id', 'feedback_text'];

    public function student() {
        return $this->belongsTo(student_tb::class, 'student_id');
    }
}
