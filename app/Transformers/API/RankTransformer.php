<?php

namespace App\Transformers\API;

use App\Models\QuizSession;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class RankTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param QuizSession $quizSession
     * @return array
     */
    public function transform(QuizSession $quizSession)
    {
        if(auth()->check()){
            if(auth()->user()->id == $quizSession->user->id){
                return [
                    'status' => 'me',
                    'result' => number_format($quizSession->result, 2),
                    'name' => $quizSession->user->first_name . ' ' . $quizSession->user->last_name,
                ];
            }
        }
        return [
            'result' => number_format($quizSession->result, 2),
            'name' => $quizSession->user->first_name . ' ' . $quizSession->user->last_name,
        ];
    }
}
