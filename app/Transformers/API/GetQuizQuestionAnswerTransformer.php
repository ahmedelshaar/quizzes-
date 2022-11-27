<?php

namespace App\Transformers\API;

use App\Models\Quiz;
use App\Models\QuizSession;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class GetQuizQuestionAnswerTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param Quiz $Quiz
     * @return array
     */
    public function transform(QuizSession $quiz_session)
    {
        return [
            "result" => $quiz_session->result,
            "correct" => $quiz_session->correct,
            "wrong" => $quiz_session->wrong,
            "quiz" => [
                "title" => $quiz_session->quiz->title,
                "description" => $quiz_session->quiz->description,
                "question" => fractal($quiz_session->quiz->questions, new QuestionAnswerTransformer())->toArray()['data']
            ]
        ];
    }
}
