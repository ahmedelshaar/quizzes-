<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSessionQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'answer' => 'object',
    ];
}
