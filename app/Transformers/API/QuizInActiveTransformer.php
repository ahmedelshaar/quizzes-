<?php

namespace App\Transformers\API;

use App\Models\QuizSchedule;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class QuizInActiveTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param QuizSchedule $schedule
     * @return array
     */
    public function transform(QuizSchedule $schedule)
    {
        return [
            'id' => $schedule->quiz->id,
            'start_at' => $schedule->start,
            'end_at' => $schedule->end,
            'title' => $schedule->quiz->title,
            'description' => $schedule->quiz->description,
        ];
    }
}
