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
                    <form method="post" action="{{ route('group.update', $group->id) }}">
                        {{ method_field('put') }}
                        <input hidden value="{{ $group->id }}" name="id" />
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">الاسم المجموعة</label>
                                <input type="text" id="first_name" value="{{ $group->name }}" class="form-control" name="name" placeholder="الاسم المجموعة" required>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('assets/vendor/list.min.js') }}"></script>
    <script src="{{ asset('assets/js/list.js') }}"></script>
@endsection
