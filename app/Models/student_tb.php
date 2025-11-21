<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class student_tb extends Model
{
    protected $table = 'student_tb';
    protected $fillable = ['name', 'email'];

}
