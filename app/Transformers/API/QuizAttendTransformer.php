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
        if($schedule->sessions->ends_at == null){
            $status = 'Continue';
        }else{
            if(Carbon::now()->isAfter($schedule->show_answer_time)){
                $status = 'Show Answer';
            }
        }

        return [
            'id' => $schedule->id,
            'start_at' => $schedule->start,
            'end_at' => $schedule->end,
            'title' => $schedule->quiz->title,
            'description' => $schedule->quiz->description,
            'status' => $status
        ];
    }
}
