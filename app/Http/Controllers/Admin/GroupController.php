<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserGroupUser;
use Couchbase\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = UserGroup::withCount('users')->get();
        return view('admin.group.index', compact('groups'))->withTitle('المجموعات');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.group.create')->withTitle('إضافة مجموعة جديدة');
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
            'name' => 'required|min:3',
        ]);
        $userGroup = UserGroup::create([
            'name' => $request->name,
        ]);
        return redirect()->route('group.show', $userGroup->id)->withSuccess('تم اضافة المجموعة جديدة بنجاح');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = UserGroup::find($id);
        $students = User::whereHas('userGroups', function ($q) use ($id){
            return $q->where('user_group_id', $id);
        })->get();
        $studentsNotGroup = User::where('role', 'user')->whereDoesntHave('userGroups')->get();
        return view('admin.group.show', compact('group', 'students', 'studentsNotGroup'))->withTitle('بيانات المجموعة: ' . $group->name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = UserGroup::find($id);
        return view('admin.group.edit', compact( 'group'))->withTitle('تعديل بيانات المجموعة: ' . $group->name);
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
            'name' => 'required|min:3',
        ]);
        $group = UserGroup::find($id);
        $group->update($request->all());
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
        $group = UserGroup::find($id);
        if($group){
            $group->delete();
            return redirect()->back()->withSuccess('تم الحذف بنجاح');
        }
        return redirect()->back()->withError('هذا المجموعة غير موجود');
    }

    public function addUserGroup(Request $request){
        UserGroupUser::create($request->all());
        return redirect()->back()->withSuccess('تم الطالب الي المجموعة بنجاح');
    }

    public function removeUserGroup(Request $request){
        UserGroupUser::where('user_id', $request->user_id)->where('user_group_id', $request->user_group_id)->delete();
        return redirect()->back()->withSuccess('تم الحذف بنجاح');
    }
}
