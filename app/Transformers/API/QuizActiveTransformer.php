<?php

namespace App\Transformers\API;

use App\Models\QuizSchedule;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class QuizActiveTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param QuizSchedule $schedule
     * @return array
     */
    public function transform(QuizSchedule $schedule)
    {
        $status = 'Coming';
        if(Carbon::now()->isAfter($schedule->start)){
            $status = 'Start';
        }
        return [
            'id' => $schedule->id,
            'start_at' => $schedule->start,
            'end_at' => $schedule->end,
            'title' => $schedule->quiz->title,
            'description' => $schedule->quiz->description,
            'status' => $status,
        ];
    }
}
