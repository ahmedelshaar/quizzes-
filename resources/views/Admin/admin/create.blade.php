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
                    <form method="post" action="{{ route('admin.store') }}">
                        <input hidden name="role" value="admin">
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">الاسم الاول</label>
                                <input type="text" id="first_name" value="{{ old('first_name') }}" class="form-control" name="first_name" placeholder="الاسم الاول" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="last_name">اسم العائلة</label>
                                <input type="text" id="last_name" value="{{ old('last_name') }}" class="form-control" name="last_name" placeholder="اسم العائلة" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="username">اسم المستخدم</label>
                                <input type="text" id="username" value="{{ old('username') }}" class="form-control" name="username" placeholder="اسم المستخدم" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="mobile">رقم الهاتف</label>
                                <input type="tel" id="mobile" value="{{ old('mobile') }}" class="form-control" name="mobile" placeholder="رقم الهاتف">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email">الايميل</label>
                                <input type="email" id="email" value="{{ old('email') }}" autocomplete="username" class="form-control" name="email" placeholder="الايميل" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="password">الرقم السري</label>
                                <input type="password" id="password" value="{{ old('password') }}" class="form-control" name="password" placeholder="الرقم السري" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="password">تاكيد الرقم السري</label>
                                <input type="password" id="password" value="{{ old('password') }}" class="form-control" name="password_confirmation" placeholder="تاكيد الرقم السري" required>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
