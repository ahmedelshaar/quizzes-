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
                    <div class="form-row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="first_name">المجموعة</label>
                            <input type="text" class="form-control" value="{{$quizSchedule['user_group']['name']}}"  readonly>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="first_name">الكويز</label>
                            <input type="text"  class="form-control" value="{{$quizSchedule['quiz']['title']}}"  readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12 col-md-6 mb-3">
                            <label>وقت البدا</label>
                            <input type="text" name="start" value="{{$quizSchedule['start']}}" id="start" class="form-control" readonly >
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>وقت النهاية</label>
                            <input type="text" name="end" value="{{$quizSchedule['end']}}" id="end" class="form-control" readonly >
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>وقت ظهور الاجابة</label>
                            <select id="answer_time" data-toggle="select" class="form-control" disabled>
                                <option value="end_quiz" @if($quizSchedule['show_answer_time'] == $quizSchedule['end']) selected @endif>ظهور الاجابة بعد انتهاء وقت الامتحان</option>
                                <option value="end_student" @if($quizSchedule['show_answer_time'] == $quizSchedule['start']) selected @endif>تظهر الإجابة للطالب فور انتهائه</option>
                                <option value="time_show_answer" @if($quizSchedule['show_answer_time'] != $quizSchedule['end'] && $quizSchedule['show_answer_time'] != $quizSchedule['start']) selected @endif>ظهور الإجابة بعد وقت محدد</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-3 d-none" id="div_time">
                            <label>وقت ظهور الاجابة</label>
                            <input type="text" id="show_answer_time" value="{{$quizSchedule['show_answer_time']}}" class="form-control" readonly >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12 col-md-6 mb-3">
                            <label>وقت السماح لدخول الامتحان</label>
                            <input type="number" min="5" id="grace_period" name="grace_period" class="form-control" value="{{$quizSchedule['grace_period']}}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        let answer_time = $('#answer_time');
        let grace_period = $('#grace_period');
        let div_time = $('#div_time');

        if(answer_time.val() != 'end_quiz' && answer_time.val() != 'end_student'){
            div_time.addClass('d-block');
        }

    </script>
@endsection
