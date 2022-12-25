<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_session_id',
        'quiz_id',
        'question_id',

    ];

    public $timestamps = false;

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
