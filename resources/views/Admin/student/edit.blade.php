@extends('layouts.dashboard')

@section('content')
    <div class="container page__heading-container">
        <div class="page__heading d-flex align-items-center justify-content-between">
            <h1 class="m-0">{{ $title }}</h1>
        </div>
        @include('inc.alert.success')
        @include('inc.alert.error')
    </div>
    <div class="container page__container">
        <div class="card card-form">
            <div class="row no-gutters">
                <div class="col-lg-12 card-body">
                    <form method="post" action="{{ route('student.update', $student->id) }}">
                        {{ method_field('put') }}
                        <input hidden value="{{ $student->id }}" name="id" />
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">الاسم الاول</label>
                                <input type="text" id="first_name" value="{{ $student->first_name }}" class="form-control" name="first_name" placeholder="الاسم الاول" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="last_name">اسم العائلة</label>
                                <input type="text" id="last_name" value="{{ $student->last_name }}" class="form-control" name="last_name" placeholder="اسم العائلة" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="username">اسم المستخدم</label>
                                <input type="text" id="username" value="{{ $student->username }}" class="form-control" name="username" placeholder="اسم المستخدم" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="mobile">رقم الهاتف</label>
                                <input type="tel" id="mobile" value="{{ $student->mobile }}" class="form-control" name="mobile" placeholder="رقم الهاتف">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email">الايميل</label>
                                <input type="email" id="email" value="{{ $student->email }}" autocomplete="username" class="form-control" name="email" placeholder="الايميل" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="select01">المجموعة</label>
                                    <select id="select01" name="user_group" data-toggle="select" class="form-control">
                                        <option value="" @if($student->group == 'ليس في مجموعة') selected @endif>اختيار مجموعة</option>
                                        @foreach($groups as $group)
                                        <option value="{{$group->id}}"
                                        @if($student->group == $group->name)
                                        selected
                                            @endif
                                            >{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="password">الرقم السري</label>
                                <input type="password" id="password" class="form-control" name="password" placeholder="الرقم السري" >
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="password">تاكيد الرقم السري</label>
                                <input type="password" id="password" class="form-control" name="password_confirmation" placeholder="تاكيد الرقم السري" >
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

