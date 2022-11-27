<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create(FeedbackRequest $request){
        Feedback::create([
            "user_id" => Auth::user()->id,
            "question_id" => $request->question_id,
            "body" => $request->body
        ]);

        return response()->json([
            'message' => 'تم إضافة الملاحظة بنجاح'
        ]);
    }

}
