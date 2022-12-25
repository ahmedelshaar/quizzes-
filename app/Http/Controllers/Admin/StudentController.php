<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentStoreRequest;
use App\Http\Requests\Admin\StudentUpdateRequest;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserGroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = User::where('role', 'user')->with('userGroups')->get();
        return view('admin.student.index', compact('students'))->withTitle('الطلاب');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = UserGroup::all();
        return view('admin.student.create', compact('groups'))->withTitle('إضافة طالب جديد');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentStoreRequest $request)
    {
        $password = Hash::make($request->password);
        $user = User::create($request->except('password') + ["password" => $password]);
        if($request->user_group){
            UserGroupUser::create([
                'user_id' => $user->id,
                'user_group_id' => $request->user_group
            ]);
        }
        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);
        return redirect()->route('student.index')->withSuccess('تم اضافة طالب جديد بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = User::find($id);
        return view('admin.student.show', compact('student'))->withTitle('بيانات الطالب ' . $student->Fullname);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::find($id);
        $groups = UserGroup::all();
        return view('admin.student.edit', compact('student', 'groups'))->withTitle('تعديل بيانات الطالب ' . $student->Fullname);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentUpdateRequest $request, $id)
    {
        $user = User::find($id);
        if($request->password == null){
            $user->update($request->except('password'));
        }else{
            $password = Hash::make($request->password);
            $user->update($request->except('password') + ["password" => $password]);
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);
        }
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
        $user = User::find($id);
        if($user){
            $user->delete();
            return redirect()->back()->withSuccess('تم الحذف بنجاح');
        }
        return redirect()->back()->withError('هذا المستخدم غير موجود');
    }
}
