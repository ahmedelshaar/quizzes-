<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SaveAnswerRequest;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizSchedule;
use App\Models\QuizSession;
use App\Models\QuizSessionQuestion;
use App\Models\UserGroupUser;
use App\Transformers\API\GetQuizQuestionAnswerTransformer;
use App\Transformers\API\GetQuizQuestionTransformer;
use App\Transformers\API\QuestionAnswerTransformer;
use App\Transformers\API\QuizActiveTransformer;
use App\Transformers\API\QuizAttendTransformer;
use App\Transformers\API\QuizInActiveTransformer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class QuizzesController extends Controller
{
    public function activeQuiz(){
        $user_group = UserGroupUser::where('user_id', auth()->user()->id)->first();
        if($user_group){
            $quizSchedules = QuizSchedule::where(DB::raw('DATE_ADD(start, interval grace_period MINUTE)'), '>=' ,Carbon::now())
                ->where('user_group_id', $user_group->user_group_id)
                ->whereDoesntHave('sessions', function ($q) use ($user_group) {
                    $q->where('user_id', $user_group->user_id);
                })->with('quiz')->orderBy('start')->get();
            if(count($quizSchedules) == 0){
                return response([
                    'message' => 'ليس لديك اي تكليف في الوقت الحال'
                ]);
            }
            return fractal($quizSchedules, new QuizActiveTransformer())->toArray()['data'];
        }
        return response([
           'message' => 'انت لست في اي مجموعة'
        ]);
    }

    public function inActiveQuiz(){
        $user_group = UserGroupUser::where('user_id', auth()->user()->id)->first();
        if($user_group){
            $quizSchedules = QuizSchedule::where(DB::raw('DATE_ADD(start, interval grace_period MINUTE)'), '<' ,Carbon::now())
                ->where('user_group_id', $user_group->user_group_id)
                ->whereDoesntHave('sessions', function ($q) use ($user_group) {
                    $q->where('user_id', $user_group->user_id);
                })->with('quiz')->get();

            if(count($quizSchedules) == 0){
                return response([
                    'message' => 'ليس لديك اي تكليفات لم تقوم بالدخول إليها'
                ]);
            }
            return fractal($quizSchedules, new QuizInActiveTransformer())->toArray()['data'];
        }
        return response([
            'message' => 'انت لست في اي مجموعة'
        ]);
    }

    public function attendQuiz(){
        $user_group = UserGroupUser::where('user_id', auth()->user()->id)->first();

        if($user_group){
            $quizSchedules = QuizSchedule::whereHas('sessions', function ($q) use ($user_group) {
                $q->where('user_id', $user_group->user_id);
            })->with('quiz','sessions')->get();
            if(count($quizSchedules) == 0){
                return response([
                    'message' => 'لم تقوم بعمل اي تكليفات'
                ]);
            }

            return fractal($quizSchedules, new QuizAttendTransformer())->toArray()['data'];
        }
        return response([
            'message' => 'انت لست في اي مجموعة'
        ]);
    }

    public function quizzes(){
        $collect = collect();
        if(is_array($this->activeQuiz())){
            $collect->push([
                'type' => 'Active',
                'quizzes' => $this->activeQuiz()
            ]);
        }
        if(is_array($this->inActiveQuiz())){
            $collect->push([
                'type' => 'inActive',
                'quizzes' => $this->inActiveQuiz()
            ]);
        }
        if(is_array($this->attendQuiz())){
            $collect->push([
                'type' => 'Attend',
                'quizzes' => $this->attendQuiz()
            ]);
        }
        if($collect->isEmpty()){
            return response([
                'message' => 'لا يوجد اي كويزات'
            ]);
        }
        return $collect;
    }

    public function getQuiz($id){
        $user_group = UserGroupUser::where('user_id', auth()->user()->id)->first();
        if($user_group) {
            $schedule = QuizSchedule::where('id', $id)->where('user_group_id', $user_group->user_group_id)->first();
            if (!$schedule) {
                return response([
                    'message' => 'هذا الامتحان غير موجود'
                ]);
            }
            if (is_array($this->inActiveQuiz())) {
                if (collect($this->inActiveQuiz())->where('id', $id)->first() != null) {
                    return response([
                        'message' => 'لم يعد الامتحان متاح بعد الان'
                    ]);
                }
            }
            $end = new Carbon($schedule->end);
            $remaining_time = $end->diffInMinutes(Carbon::now());
            if (is_array($this->activeQuiz())) {
                $active_schedule = collect($this->activeQuiz())->where('id', $id)->first();
                if ($active_schedule != null) {
                    if ($active_schedule['status'] == 'Coming') {
                        return response([
                            'message' => 'الامتحان لما يبدا بعد'
                        ]);
                    }
                    QuizSession::create([
                        'user_id' => auth()->user()->id,
                        'quiz_schedules_id' => $id,
                        'quiz_id' => $schedule->quiz_id,
                        'starts_at' => Carbon::now()
                    ]);
                    $questions = Quiz::where('id', $schedule->quiz_id)->with('questions')->first();
                    return response([
                        'remaining_time' => $remaining_time,
                        'quiz_schedules_id' => $id,
                        'quiz' => fractal($questions, new GetQuizQuestionTransformer())->toArray()['data']
                    ]);
                }
            }
            if (is_array($this->attendQuiz())) {
                $quiz_attend = collect($this->attendQuiz())->where('id', $id)->first();
                $session = QuizSession::with('quiz')->where('user_id', auth()->user()->id)->where('quiz_schedules_id', $id)->first();
                if ($quiz_attend['status'] == 'In Correcting') {
                    return response([
                        'message' => 'يتم تصحيح الامتحان'
                    ]);
                } else if ($quiz_attend['status'] == 'Continue') {
                    $end_schedule = new Carbon($schedule->end);
                    $end_schedule->addMinutes(5);
                    if ($session->ends_at == null && $end_schedule->isBefore(Carbon::now())) {
                        $session->delete();
                    }
                    $questions = Quiz::where('id', $schedule->quiz_id)->with('questions')->first();
                    return response([
                        'remaining_time' => $remaining_time,
                        'quiz_schedules_id' => $id,
                        'quiz' => fractal($questions, new GetQuizQuestionTransformer())->toArray()['data']
                    ]);
                } else {
                    $questions_with_answer = QuizSessionQuestion::where('quiz_session_id', $session->id)
                        ->where('user_id', auth()->user()->id)->get();
                    return [
                        "result" => $session->result,
                        "correct" => $session->correct,
                        "wrong" => $session->wrong,
                        "quiz" => [
                            "title" => $session->quiz->title,
                            "description" => $session->quiz->description,
                            "questions" => fractal($questions_with_answer, new QuestionAnswerTransformer())->toArray()['data']
                        ]
                    ];
                }
            }
        }
        return response([
            'message' => 'انت لست في اي مجموعة'
        ]);
    }

    public function saveAnswer(SaveAnswerRequest $request){
        $user_id = auth()->user()->id;
        $session = QuizSession::where('quiz_schedules_id', $request->quiz_schedules_id)
            ->where('user_id', $user_id)
            ->first();
        $user_group = UserGroupUser::where('user_id', auth()->user()->id)->first();
        $schedule = QuizSchedule::where('id', $request->quiz_schedules_id)->where('user_group_id', $user_group->user_group_id)->first();
        if(!$schedule){
            return response([
                'message' => 'هذا الامتحان غير موجود'
            ]);
        }

        if($session->ends_at == null) {
            $end_schedule = new Carbon($schedule->end);
            $end_schedule->addMinutes(5);

            if($end_schedule->isBefore(Carbon::now())){
                $session->delete();
                return response([
                    'message' => "لقد مر الوقت المسموح بيه لحفظ الاجابات"
                ]);
            }

            $questions = QuizQuestion::where('quiz_id', $session->quiz_id)->with('question')->get();
            $answers = collect($request->questions);
            foreach ($questions as $question){
                $answer = $answers->where('id', $question->question->id)->first();
                $data = [
                    'quiz_session_id' => $session->id,
                    'user_id' => $user_id,
                    'question_id' => $question->question->id,
                    'is_correct' => 0,
                ];
                if($answer == null){
                    try{
                        QuizSessionQuestion::create($data + [
                            'question' => $question->question->question,
                            'type' => $question->question->type,
                            'options' => $question->question->options,
                            'correct_answer' => $question->question->correct_answer,
                            'hint' => $question->question->hint,
                            'solution' => $question->question->solution,
                            'image' => $question->question->image,
                        ]);
                    }catch (\Exception $e){}
                }else{
                    if($question->question->type == 'Writing'){
                        $data = [
                            'quiz_session_id' => $session->id,
                            'user_id' => $user_id,
                            'question_id' => $question->question->id,
                            'answer' => $answer['answer'],
                        ];
                        $flag = false;
                    }else{
                        $correct_answer = $question->question->correct_answer;
                        $student_answer = $answer['answer'];
                        array_multisort($correct_answer);
                        array_multisort($student_answer);
                        $data = [
                            'quiz_session_id' => $session->id,
                            'user_id' => $user_id,
                            'question_id' => $question->question->id,
                            'answer' => $answer['answer'],
                            'is_correct' => $correct_answer == $student_answer ? 1 : 0
                        ];
                    }
                    try {
                        QuizSessionQuestion::create($data + [
                            'question' => $question->question->question,
                            'type' => $question->question->type,
                            'options' => $question->question->options,
                            'correct_answer' => $question->question->correct_answer,
                            'hint' => $question->question->hint,
                            'solution' => $question->question->solution,
                            'image' => $question->question->image,
                        ]);
                    }catch (\Exception $e){}
                }
            }
            $correct = QuizSessionQuestion::where('quiz_session_id', $session->id)
                ->where('user_id', $user_id)->get();
            $all = $correct->whereNotNull('is_correct')->count();
            $correct = $correct->sum('is_correct');
            $session->update([
                'correct' => $correct,
                'wrong' => $all - $correct,
                'result' => round($correct / $all * 100, 2),
                'ends_at' => Carbon::now(),
            ]);
            return response([
                'message' => "تم حفظ الاجابات بنجاح"
            ]);
        }else{
            return response([
                'message' => "لقد قمت بحفظ الاجابة بالفعل"
            ]);
        }
    }
}


