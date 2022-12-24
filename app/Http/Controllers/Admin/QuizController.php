<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::withCount('questions', 'sessions')->get();
        return view('admin.quiz.index', compact('quizzes'))->withTitle('الكويزات');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.quiz.create')->withTitle('إضافة كويز جديدة');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|min:3',
        ]);
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return redirect()->route('quiz.show', $quiz->id)->withSuccess('تم اضافة الكويز جديد بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quiz = Quiz::where('id',$id)->with('questions')->first();
        $questionsNotQuizzes = DB::table('questions')
            ->whereNotIn('id', function($query) use ($quiz)
            {
                $query->select('question_id')
                    ->from('quiz_questions')
                    ->whereRaw('quiz_questions.quiz_id = ' . $quiz->id);
            })
        ->get();
        return view('admin.quiz.show', compact('quiz', 'questionsNotQuizzes'))->withTitle('بيانات الكويز: ' . $quiz->title);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quiz = Quiz::find($id);
        return view('admin.quiz.edit', compact( 'quiz'))->withTitle('تعديل بيانات الكويز: ' . $quiz->title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|min:3',
        ]);
        $quiz = Quiz::find($id);
        $quiz->update($request->all());
        return redirect()->back()->withSuccess('تم تحديث البيانات بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if($quiz){
            $quiz->delete();
            return redirect()->back()->withSuccess('تم الحذف بنجاح');
        }
        return redirect()->back()->withError('هذا الكويز غير موجود');
    }

    public function addQuestionQuiz(Request $request){
        QuizQuestion::create($request->all());
        return redirect()->back()->withSuccess('تم السؤال الي الكويز بنجاح');
    }

    public function removeQuestionQuiz(Request $request){
        QuizQuestion::where('quiz_id', $request->quiz_id)->where('question_id', $request->question_id)->delete();
        return redirect()->back()->withSuccess('تم الحذف بنجاح');
    }
}
