<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dateFormat = 'U';

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function sessions()
    {
        return $this->hasOne(QuizSession::class, 'quiz_schedules_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
}
