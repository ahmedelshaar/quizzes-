<?php

namespace App\Transformers\API;

use App\Models\QuizSchedule;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class QuizAttendTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param QuizSchedule $schedule
     * @return array
     */
    public function transform(QuizSchedule $schedule)
    {
        $status = 'In Correcting';
        if(Carbon::now()->isAfter($schedule->show_answer_time)){
            if($schedule->sessions->ends_at){
                $status = 'Show Answer';
            }else{
                $status = 'Continue';
            }
        }
        return [
            'id' => $schedule->quiz->id,
            'start_at' => $schedule->start,
            'end_at' => $schedule->end,
            'title' => $schedule->quiz->title,
            'description' => $schedule->quiz->description,
            'status' => $status
        ];
    }
}
