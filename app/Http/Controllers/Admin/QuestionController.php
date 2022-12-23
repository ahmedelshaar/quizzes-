<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::all();
        return view('admin.question.index', compact('questions'))->withTitle('الاسئلة');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $years = ['سؤال عام', 'سنة اولي', 'سنة تانية', 'سنة تالتة'];
        $types = ['MCQ Single Answer', 'MCQ Multi-Answer', 'True - False', 'Writing'];
        return view('admin.question.create', compact('years', 'types'))->withTitle('اضافة سؤال');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        if($request->type == 'Writing'){
            Question::create($request->all());
        }else if($request->type == 'True - False'){
            Question::create($request->except('check') + ['correct_answer' => [$request->check]]);
        }else if($request->type == 'MCQ Single Answer'){
            Question::create($request->except('answer') + ['correct_answer' => [$request->answer]]);
        }else{
            $correct_answer = collect($request->answer)->keys();
            Question::create($request->except('answer') + ['correct_answer' => $correct_answer]);
        }
        return redirect()->route('question.index')->withSuccess('تم اضافة السؤال جديد بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);
        if($question){
            $question->delete();
            return redirect()->back()->withSuccess('تم الحذف بنجاح');
        }
        return redirect()->back()->withError('هذا السؤال غير موجود');
    }
}
