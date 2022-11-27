<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\QuizzesController;
use App\Http\Controllers\API\RankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    ## Get Quizzes
    Route::get('/quiz/active', [QuizzesController::class, 'activeQuiz']);
    Route::get('/quiz/inactive', [QuizzesController::class, 'inActiveQuiz']);
    Route::get('/quiz/attend', [QuizzesController::class, 'attendQuiz']);
    Route::get('/quiz/{id}', [QuizzesController::class, 'getQuiz'])->name('api.getQuiz');
    Route::get('/quizzes', [QuizzesController::class, 'quizzes']);

    ## Save Answer
    Route::post('/quiz/{id}', [QuizzesController::class, 'saveAnswer']);

    ## Rank
    Route::get('/rank/site', [RankController::class, 'site']);
    Route::get('/rank/quiz/{id}', [RankController::class, 'quiz']);
    Route::get('/rank/group', [RankController::class, 'group']);

    ## feedback
    Route::post('/feedback/create', [FeedbackController::class, 'create']);

    ## Student
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/update', [AuthController::class, 'update']);

});
