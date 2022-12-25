<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\ProfileController;
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


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function (){
    Route::get('/', [DashboardController::class, 'index'])->name('admin_dashboard');

    Route::resource('student', StudentController::class);
    Route::resource('admin', AdminController::class);
    Route::resource('group', GroupController::class);
    Route::resource('question', QuestionController::class);
    Route::resource('quiz', QuizController::class);
    Route::resource('schedule', ScheduleController::class);
    Route::resource('feedback', FeedbackController::class);

    Route::post('group/add', [GroupController::class, 'addUserGroup'])->name('addUserGroup');
    Route::post('group/remove', [GroupController::class, 'removeUserGroup'])->name('removeUserGroup');

    Route::post('quiz/add', [QuizController::class, 'addQuestionQuiz'])->name('addQuestionQuiz');
    Route::post('quiz/remove', [QuizController::class, 'removeQuestionQuiz'])->name('removeQuestionQuiz');
});



Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'user']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
