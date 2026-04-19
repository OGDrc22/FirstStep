<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrResults extends Model
{
    protected $table = 'qr_table';

    protected $fillable = [
        'token',
        'payload',
        'expires_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime'
    ];
}
