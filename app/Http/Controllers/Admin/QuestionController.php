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
        $filename = null;
        if($request->has('image')){
            $filename = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/questions'), $filename);
            $filename = 'images/questions/' . $filename;
        }
        if($request->type == 'Writing'){
            Question::create($request->except('image'), ['image' => $filename]);
        }else if($request->type == 'True - False'){
            Question::create($request->except('check', 'image') + ['correct_answer' => [$request->check], 'image' => $filename]);
        }else if($request->type == 'MCQ Single Answer'){
            Question::create($request->except('answer', 'image') + ['correct_answer' => [$request->answer], 'image' => $filename]);
        }else{
            $correct_answer = collect($request->answer)->keys();
            Question::create($request->except('answer', 'image') + ['correct_answer' => $correct_answer, 'image' => $filename]);
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
        $question = Question::find($id);
        return view('admin.question.show', compact('question'))->withTitle('سؤال: ' . $question->question);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        $years = ['سؤال عام', 'سنة اولي', 'سنة تانية', 'سنة تالتة'];
        $types = ['MCQ Single Answer', 'MCQ Multi-Answer', 'True - False', 'Writing'];
        return view('admin.question.edit', compact('question', 'years', 'types'))->withTitle('سؤال: ' . $question->question);
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
        $question = Question::find($id);
        $filename = $question->image;
        if($request->has('image')){
            $filename = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/questions'), $filename);
            $filename = 'images/questions/' . $filename;
        }
        if($request->type == 'Writing'){
            $question->update($request->except('image'), ['image' => $filename]);
        }else if($request->type == 'True - False'){
            $question->update($request->except('check', 'image') + ['correct_answer' => [$request->check], 'image' => $filename]);
        }else if($request->type == 'MCQ Single Answer'){
            $question->update($request->except('answer', 'image') + ['correct_answer' => [$request->answer], 'image' => $filename]);
        }else{
            $correct_answer = collect($request->answer)->keys();
            $question->update($request->except('answer', 'image') + ['correct_answer' => $correct_answer, 'image' => $filename]);
        }
        return redirect()->back()->withSuccess('تم تحديث السؤال جديد بنجاح');
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
