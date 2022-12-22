<?php

use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {

    return view('layouts.dashboard');
});

Route::group(['prefix' => 'admin'], function (){
    Route::resource('student', StudentController::class);
    Route::resource('group', GroupController::class);
    Route::resource('question', QuestionController::class);
    Route::resource('quiz', QuizController::class);
    Route::resource('schedule', ScheduleController::class);
    Route::resource('feedback', FeedbackController::class);


    Route::post('group/add', [GroupController::class, 'addUserGroup'])->name('addUserGroup');
    Route::post('group/remove', [GroupController::class, 'removeUserGroup'])->name('removeUserGroup');
});
