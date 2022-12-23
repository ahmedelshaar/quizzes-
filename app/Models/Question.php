<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'type',
        'year',
        'options',
        'correct_answer',
        'hint',
        'solution',
        'image',
    ];

    public $timestamps = false;

    protected $casts = [
        'correct_answer' => 'array',
        'options' => 'object',
    ];

    public function answer(){
        return $this->hasOne(QuizSessionQuestion::class, 'question_id', 'id');
//            ->withPivot('answer', 'is_correct', 'question_id', 'user_id');
    }
}
