<?php

namespace App\Transformers\API;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSessionQuestion;
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
    public function transform(QuizSessionQuestion $question)
    {
        if($question->type == 'Writing' || $question->type == 'True - False'){
            return [
                'id' => $question->id,
                "title" => $question->question,
                "type" => $question->type,
                "hint" => $question->hint,
                "image" => $question->image,
                "correct_answer" =>  $question->correct_answer,
                "solution" =>  $question->solution,
                "answer" => $question->answer,
                "is_correct" => $question->is_correct,
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
            "answer" => $question->answer,
            'is_correct' => $question->is_correct
        ];
    }
}
