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
                    <form method="post" action="{{ route('quiz.update', $quiz->id) }}">
                        {{ method_field('put') }}
                        <input hidden value="{{ $quiz->id }}" name="id" />
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">الاسم الكويز</label>
                                <input type="text" id="name" value="{{ $quiz->title }}" class="form-control" name="title" placeholder="اسم الكويز" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">وصف الكويز</label>
                                <input type="text" value="{{ $quiz->description  }}" class="form-control" name="description" placeholder="وصف الكويز" >
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
