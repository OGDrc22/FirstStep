<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class student_tb extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'student_tb';
    protected $fillable = ['name', 'email'];

    protected $hidden = [];

    public function examResults() {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

}
