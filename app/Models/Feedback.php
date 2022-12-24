<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'body',
    ];

    public function student(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function question(){
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
