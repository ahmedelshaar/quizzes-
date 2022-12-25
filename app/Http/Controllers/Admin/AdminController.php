<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentStoreRequest;
use App\Http\Requests\Admin\StudentUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'admin')->with('userGroups')->get();
        return view('admin.admin.index', compact('students'))->withTitle('المديرين');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin.create')->withTitle('إضافة مدير جديد');
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
        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);
        return redirect()->route('admin.index')->withSuccess('تم اضافة مدير جديد بنجاح');
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
        return view('admin.admin.show', compact('student'))->withTitle('بيانات المدير ' . $student->Fullname);
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
        return view('admin.admin.edit', compact('student'))->withTitle('تعديل بيانات المدير ' . $student->Fullname);
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
        if(User::where('role', 'admin')->count() == 1){
            return redirect()->back()->withError('يجب ان يكون هناك مدير واحد علي الاقل');
        }
        $user = User::find($id);
        if($user){
            $user->delete();
            return redirect()->back()->withSuccess('تم الحذف بنجاح');
        }
        return redirect()->back()->withError('هذا المدير غير موجود');
    }
}
