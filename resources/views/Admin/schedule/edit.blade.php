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
                    <form method="post" action="{{ route('schedule.update', $quizSchedule->id) }}">
                        {{ method_field('put') }}
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">المجموعة</label>
                                <select id="select01" name="user_group_id" data-toggle="select" class="form-control" required>
                                    <option value="">اختيار مجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}"
                                                @if($quizSchedule->user_group_id == $group->id)
                                                selected
                                            @endif
                                        >{{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name">الكويز</label>
                                <select id="select01" name="quiz_id" data-toggle="select" class="form-control" required>
                                    <option value="" @if(!old('quiz_id')) selected @endif>اختيار الكويز</option>
                                    @foreach($quizzes as $quiz)
                                        <option value="{{$quiz->id}}"
                                            @if($quizSchedule->quiz_id == $quiz->id)
                                                selected
                                            @endif
                                        >{{$quiz->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label>وقت البدا</label>
                                <input type="text" name="start" value="{{$quizSchedule->start}}" id="start" class="form-control" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-alt-format="Y-m-d h:i K" data-flatpickr-date-format="Y-m-d H:i" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label>وقت النهاية</label>
                                <input type="text" name="end" value="{{$quizSchedule->end}}" id="end" class="form-control" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-alt-format="Y-m-d h:i K" data-flatpickr-date-format="Y-m-d H:i" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label>وقت ظهور الاجابة</label>
                                <select id="answer_time" data-toggle="select" class="form-control">
                                    <option value="end_quiz" @if($quizSchedule->show_answer_time == $quizSchedule->end) selected @endif>ظهور الاجابة بعد انتهاء وقت الامتحان</option>
                                    <option value="end_student" @if($quizSchedule->show_answer_time == $quizSchedule->start) selected @endif>تظهر الإجابة للطالب فور انتهائه</option>
                                    <option value="time_show_answer" @if($quizSchedule->show_answer_time != $quizSchedule->end and $quizSchedule->show_answer_time != $quizSchedule->start) selected @endif>ظهور الإجابة بعد وقت محدد</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3 d-none" id="div_time">
                                <label>وقت ظهور الاجابة</label>
                                <input type="text" id="show_answer_time" value="{{$quizSchedule->show_answer_time}}" name="show_answer_time" class="form-control" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-alt-format="Y-m-d h:i K" data-flatpickr-date-format="Y-m-d H:i" required >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label>وقت السماح لدخول الامتحان</label>
                                <input type="number" min="5" id="grace_period" name="grace_period" class="form-control" value="{{$quizSchedule->grace_period}}" required>
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
    <script>
        let start = document.querySelector('#start')._flatpickr;
        let end = document.querySelector('#end')._flatpickr;
        let show_answer_time = document.querySelector('#show_answer_time')._flatpickr;

        let answer_time = $('#answer_time');
        let grace_period = $('#grace_period');
        let div_time = $('#div_time');

        start.config.minDate = 'today';
        end.config.minDate = 'today';
        show_answer_time.config.minDate = 'today';


        start.config.onChange.push(function (selectedDates) {
            end.config.minDate = selectedDates[0];
            if(answer_time.val() == 'end_student') {
                show_answer_time.setDate(start.selectedDates[0])
            }
        })

        end.config.onChange.push(function (selectedDates) {
            if(answer_time.val() == 'end_quiz') {
                show_answer_time.setDate(end.selectedDates[0])
            }
        })

        answer_time.change(function (){
            if($(this).val() == 'end_quiz'){
                div_time.removeClass('d-block');
                show_answer_time.setDate(end.selectedDates[0])
            }else if($(this).val() == 'end_student'){
                div_time.removeClass('d-block');
                show_answer_time.setDate(start.selectedDates[0])
            }else{
                div_time.addClass('d-block');
            }
        })

        if(answer_time.val() != 'end_quiz' && answer_time.val() != 'end_student'){
            div_time.addClass('d-block');
        }

    </script>
@endsection
