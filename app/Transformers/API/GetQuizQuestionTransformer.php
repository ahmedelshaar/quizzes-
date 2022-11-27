<?php

namespace App\Transformers\API;

use App\Models\Quiz;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class GetQuizQuestionTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param Quiz $Quiz
     * @return array
     */
    public function transform(Quiz $quiz)
    {
        return [
            'id' => $quiz->id,
            "title" => $quiz->title,
            "description" => $quiz->description,
            "questions" => fractal($quiz->questions, new QuestionTransformer())->toArray()['data']
        ];
    }
}
