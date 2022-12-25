<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSessionQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'correct_answer' => 'array',
        'options' => 'object',
        'answer' => 'object',
    ];

    public $timestamps = false;
}
