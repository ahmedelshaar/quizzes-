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
                            <label>السؤال</label>
                            <textarea style="resize: none;" readonly type="text" class="form-control" rows="8" name="question" placeholder="السؤال" required>{{$question->question}}</textarea>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-group">
                                <label for="select01">السنة</label>
                                <input type="text" value="{{ $question->year }}" readonly class="form-control" name="hint" placeholder="hint">
                            </div>
                            <div class="form-group">
                                <label>توضيح (hint)</label>
                                <input type="text" value="{{ $question->hint }}" readonly class="form-control" name="hint" placeholder="hint">
                            </div>
                            @if($question->image != null)
                                <div class="input-group mb-3">
                                    <label>الصورة</label>
                                    <img src="{{ asset($question->image) }}" width="100%" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="select01">نوع السؤال</label>
                            <input type="text" value="{{ $question->type }}" readonly class="form-control" name="hint" placeholder="hint">
                        </div>
                        @if($question->type == 'MCQ Multi-Answer')
                            <div class="mb-3">
                                <label>الاختيار الاول</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer" @if(in_array('A', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->A}}" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التاني</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer" @if(in_array('B', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->B}}" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التالت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer" @if(in_array('C', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->C}}" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار الرابع</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer" @if(in_array('D', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->D}}" readonly>
                                </div>
                            </div>
                        @elseif($question->type == 'MCQ Single Answer')
                            <div class="mb-3">
                                <label>الاختيار الاول</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" @if(in_array('A', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->A}}" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التاني</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" @if(in_array('B', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->B}}"  readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التالت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" @if(in_array('C', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->C}}" required readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار الرابع</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" @if(in_array('D', $question->correct_answer)) checked @endif disabled>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->D}}" required readonly>
                                </div>
                            </div>
                        @elseif($question->type == 'True - False')
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>True</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="check" disabled value="A" @if($question->correct_answer[0] == 'A') checked @endif required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="true" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>False</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="check" disabled value="B" @if($question->correct_answer[0] == 'B') checked @endif>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="false" readonly>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label>الاجابة (تظهر بعد التصحيح)</label>
                                <textarea style="resize: none;" type="text" class="form-control" rows="8" readonly name="solution" placeholder="الاجابة" required>{{$question->solution}}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

