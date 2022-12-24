<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizSchedule;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = QuizSchedule::with('quiz', 'userGroup')->get()->toArray();
        return view('admin.schedule.index', compact('schedules'))->withTitle('التكليفات');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = UserGroup::all();
        $quizzes = Quiz::all();
        return view('admin.schedule.create', compact('quizzes', 'groups'))->withTitle('اضافة تكليف جديد');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        QuizSchedule::create($request->all());
        return redirect()->route('schedule.index')->withSuccess('تم اضافة تكليف جديد بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quizSchedule = QuizSchedule::where('id',$id)->with('quiz', 'userGroup')->first()->toArray();
        return view('admin.schedule.show', compact('quizSchedule'))->withTitle('التكليف');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $groups = UserGroup::all();
        $quizzes = Quiz::all();
        $quizSchedule = QuizSchedule::find($id);
        return view('admin.schedule.edit', compact('quizzes','quizSchedule', 'groups'))->withTitle('تحديث التكليف');
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
        $quizSchedule = QuizSchedule::find($id);
        $quizSchedule->update($request->all());
        return redirect()->route('schedule.index')->withSuccess('تم تحديث البيانات بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = QuizSchedule::find($id);
        if($schedule){
            $schedule->delete();
            return redirect()->back()->withSuccess('تم الحذف بنجاح');
        }
        return redirect()->back()->withError('هذا التكليف غير موجود');
    }
}
