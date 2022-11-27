<?php

namespace App\Transformers\API;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class QuestionTransformer extends TransformerAbstract
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
                "image" => $question->image
            ];
        }
        return [
            'id' => $question->id,
            "title" => $question->question,
            "type" => $question->type,
            "hint" => $question->hint,
            "image" => $question->image,
            "options" => $question->options
        ];
    }
}
