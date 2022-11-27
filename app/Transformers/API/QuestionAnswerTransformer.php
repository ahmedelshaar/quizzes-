<?php

namespace App\Transformers\API;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class QuestionAnswerTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param Question $question
     * @return array
     */
    public function transform(Question $question)
    {
        if($question->type == 'Writing'){
            return [
                'id' => $question->id,
                "title" => $question->question,
                "type" => $question->type,
                "hint" => $question->hint,
                "image" => $question->image,
                "correct_answer" =>  $question->correct_answer,
                "solution" =>  $question->solution,
                "answer" => $question->answer->answer ?? null,
                "is_correct" => $question->answer->is_correct ?? null,
            ];
        }
        return [
            'id' => $question->id,
            "title" => $question->question,
            "type" => $question->type,
            "hint" => $question->hint,
            "image" => $question->image,
            "options" => $question->options,
            "correct_answer" =>  $question->correct_answer,
            "solution" =>  $question->solution,
            "answer" => $question->answer->answer ?? null,
            'is_correct' => $question->answer->is_correct ?? null
        ];
    }
}
