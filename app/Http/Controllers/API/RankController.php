<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\QuizSchedule;
use App\Models\QuizSession;
use App\Models\UserGroupUser;
use App\Transformers\API\GetQuizQuestionTransformer;
use App\Transformers\API\RankTransformer;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function site(){
        $rank =  QuizSession::selectRaw('AVG(result) result, user_id')
            ->whereNotNull('ends_at')
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('result', 'desc')
            ->get();
        return fractal($rank, new RankTransformer())->toArray()['data'];
    }

    public function quiz($id){
        $check = QuizSession::where('quiz_id', $id)->where('user_id', auth()->user()->id)->first();
        if($check){
            $rank = QuizSession::selectRaw('AVG(result) result, user_id')
                ->where('quiz_id', $id)
                ->whereNotNull('ends_at')
                ->with('user')
                ->groupBy('user_id')
                ->orderBy('result', 'desc')
                ->get();
            return fractal($rank, new RankTransformer())->toArray()['data'];

        }
        return response([
           'message' => 'غير مسموح لك برؤية الترتيب'
        ]);
    }

    public function group(){
        $user_group = UserGroupUser::where('user_id', auth()->user()->id)->first();
        if($user_group) {
            $quiz_ids = QuizSchedule::where('user_group_id', $user_group->user_group_id)->pluck('quiz_id');
            $rank = QuizSession::selectRaw('AVG(result) result, user_id')
                ->whereIn('quiz_id', $quiz_ids)
                ->whereNotNull('ends_at')
                ->with('user')
                ->groupBy('user_id')
                ->orderBy('result', 'desc')
                ->get();
            return fractal($rank, new RankTransformer())->toArray()['data'];
        }
        return response([
            'message' => 'انت لست في اي مجموعة'
        ]);
    }
}
