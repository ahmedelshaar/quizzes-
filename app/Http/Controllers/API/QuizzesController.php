<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizSchedule;
use App\Models\QuizSession;
use App\Models\UserGroupUser;
use App\Transformers\API\GetQuizQuestionAnswerTransformer;
use App\Transformers\API\GetQuizQuestionTransformer;
use App\Transformers\API\QuizActiveTransformer;
use App\Transformers\API\QuizAttendTransformer;
use App\Transformers\API\RankTransformer;
use Illuminate\Http\Request;
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
            return fractal($quizSchedules, new RankTransformer())->toArray()['data'];
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
        if($user_group){
            $inactive = QuizSchedule::where(DB::raw('DATE_ADD(start, interval grace_period MINUTE)'), '<' ,Carbon::now())
                ->where('user_group_id', $user_group->user_group_id)
                ->where('quiz_id', $id)
                ->whereDoesntHave('sessions', function ($q) use ($user_group) {
                    $q->where('user_id', $user_group->user_id);
                })->with('quiz')->first();
            if($inactive){
                return response([
                    'message' => 'لم يعد الامتحان متاح بعد الان'
                ]);
            }
            $active_schedule = QuizSchedule::where(DB::raw('DATE_ADD(start, interval grace_period MINUTE)'), '>=' ,Carbon::now())
                ->where('user_group_id', $user_group->user_group_id)
                ->where('quiz_id', $id)
                ->whereDoesntHave('sessions', function ($q) use ($user_group) {
                    $q->where('user_id', $user_group->user_id);
                })->with('quiz')->orderBy('start')->first();
            $active_quiz = fractal($active_schedule, new QuizActiveTransformer())->toArray()['data'];
            if($active_quiz){
                if($active_quiz['status'] == 'Start'){
                    $end = new Carbon($active_schedule->end);
                    $end_time = $end->diffInMinutes(Carbon::now());
                    $time_quiz = $active_schedule->time;
                    $remaining_time = 0;
                    if($time_quiz <= $end_time){
                        $remaining_time = $time_quiz;
                    }else{
                        $remaining_time = $end_time;
                    }
                    QuizSession::create([
                        'user_id' =>  auth()->user()->id,
                        'quiz_schedules_id' => $active_schedule->id,
                        'quiz_id' => $active_schedule->quiz->id,
                        'starts_at' => Carbon::now()
                    ]);
                    $questions = Quiz::where('id', $id)->with('questions')->first();
                    return response([
                        'remaining_time' => $remaining_time,
                        'quiz_schedules_id' => $active_schedule->id,
                        'quiz' => fractal($questions, new GetQuizQuestionTransformer())->toArray()['data']
                    ]);
                }else{
                    return response([
                        'message' => 'الامتحان لما يبدا بعد'
                    ]);
                }
            }else{
                $quiz_attend = QuizSchedule::where('quiz_id', $id)
                ->whereHas('sessions', function ($q) use ($user_group) {
                    $q->where('user_id', $user_group->user_id);
                })->with('quiz','sessions')->first();
                if($quiz_attend->sessions->ends_at){
                    $questions_with_answer = QuizSession::where('quiz_schedules_id', $quiz_attend->id)
                    ->where('user_id', auth()->user()->id)
                    ->with(['quiz' => function ($quiz) use($quiz_attend) {
                        return $quiz->with([
                            'questions' => function ($question) use($quiz_attend) {
                                return $question->with([
                                    'answer' => function ($type) use($quiz_attend) {
                                        return $type->where('quiz_session_id', $quiz_attend->sessions->id)
                                            ->where('user_id', auth()->user()->id);
                                    }
                                ]);
                            }
                        ]);
                    }])->first();
                    $show_answer = new Carbon($quiz_attend->show_answer_time);
                    if($show_answer->isAfter(Carbon::now())){
                        return response([
                           'message' => 'يتم تصحيح الامتحان'
                        ]);
                    }
                    return fractal($questions_with_answer, new GetQuizQuestionAnswerTransformer())->toArray()['data'];
                }else{
                    $end = new Carbon($quiz_attend->end);
                    $end_time = $end->diffInMinutes(Carbon::now());
                    if($end->isBefore(Carbon::now())){
                        $end_time *= -1;
                    }
                    $time_quiz = $quiz_attend->time;
                    $remaining_time = 0;
                    if($time_quiz <= $end_time){
                        $remaining_time = $time_quiz;
                    }else{
                        $remaining_time = $end_time;
                    }

                    if($remaining_time < 0){
                        $quiz = QuizSession::where('id', $quiz_attend->sessions->id)->first();
                        $quiz->update([
                            'ends_at' => Carbon::now(),
                            "result" => 0,
                            "correct" => 0,
                            "wrong" => 0,
                        ]);
                        return redirect()->route('api.getQuiz', $id);
                    }
                    $questions = Quiz::where('id', $id)->with('questions')->first();
                    return response([
                        'remaining_time' => $remaining_time,
                        'quiz_schedules_id' => $quiz_attend->id,
                        'quiz' => fractal($questions, new GetQuizQuestionTransformer())->toArray()['data']
                    ]);
                }
            }
        }
        return response([
            'message' => 'انت لست في اي مجموعة'
        ]);
    }

    public function saveAnswer(){

    }

}


