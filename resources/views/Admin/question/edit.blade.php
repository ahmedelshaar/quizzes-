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
                <form method="post" action="{{ route('question.update', $question->id) }}" enctype="multipart/form-data">
                    {{ method_field('put') }}
                    <div class="form-row">
                        <div class="col-12 col-md-6 mb-3">
                            <label>السؤال</label>
                            <textarea style="resize: none;" type="text" class="form-control" rows="8" name="question" placeholder="السؤال" required>{{$question->question}}</textarea>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-group">
                                <label for="select01">السنة</label>
                                <select id="select01" name="year" data-toggle="select" class="form-control" required>
                                    <option value="" @if(!old('year')) selected @endif>اختيار السنة</option>
                                    @foreach($years as $year)
                                        <option value="{{$year}}"
                                                @if($question->year == $year)
                                                selected
                                            @endif
                                        >{{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>توضيح (hint)</label>
                                <input type="text" value="{{ $question->hint }}" class="form-control" name="hint" placeholder="hint">
                            </div>
                            <div class="input-group mb-3">
                                @if($question->image != null)
                                    <div class="input-group mb-3">
                                        <label>الصورة</label>
                                        <img src="{{ asset($question->image) }}" width="100%" />
                                    </div>
                                @endif
                                <div class="input-group-prepend">
                                    <span class="input-group-text" >صورة</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image">
                                    <label class="custom-file-label" >Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="select01">نوع السؤال</label>
                            <select id="type" name="type" data-toggle="select" class="form-control" required>
                                <option value="" @if(!old('type')) selected @endif>نوع السؤال</option>
                                @foreach($types as $type)
                                    <option value="{{$type}}"
                                            @if($question->type == $type)
                                            selected
                                        @endif
                                    >{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="answer">
                        @if($question->type == 'MCQ Multi-Answer')
                            <div class="mb-3">
                                <label>الاختيار الاول</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[A]" @if(in_array('A', $question->correct_answer)) checked @endif>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->A}}" name="options[A]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التاني</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[B]" @if(in_array('B', $question->correct_answer)) checked @endif>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->B}}" name="options[B]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التالت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[C]" @if(in_array('C', $question->correct_answer)) checked @endif>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->C}}" name="options[C]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار الرابع</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[D]" @if(in_array('D', $question->correct_answer)) checked @endif>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->D}}" name="options[D]" required>
                                </div>
                            </div>
                        @elseif($question->type == 'MCQ Single Answer')
                            <div class="mb-3">
                                <label>الاختيار الاول</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="A" @if(in_array('A', $question->correct_answer)) checked @endif>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->A}}" required name="options[A]">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التاني</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="B" @if(in_array('B', $question->correct_answer)) checked @endif >
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->B}}" required name="options[B]">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التالت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="C" @if(in_array('C', $question->correct_answer)) checked @endif >
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->C}}" required name="options[C]">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار الرابع</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="D" @if(in_array('D', $question->correct_answer)) checked @endif >
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$question->options->D}}" required name="options[D]" >
                                </div>
                            </div>
                        @elseif($question->type == 'True - False')
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>True</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="check"  value="A" @if($question->correct_answer[0] == 'A') checked @endif required>
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
                                                <input type="radio" name="check"  value="B" @if($question->correct_answer[0] == 'B') checked @endif>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="false" readonly>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label>الاجابة (تظهر بعد التصحيح)</label>
                                <textarea style="resize: none;" type="text" class="form-control" rows="8" name="solution" placeholder="الاجابة" required>{{$question->solution}}</textarea>
                            </div>
                        @endif
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
        $('#type').change(function () {
            if ($(this).val() == 'MCQ Multi-Answer') {
                $('#answer').html(`<div class="mb-3">
                                <label>الاختيار الاول</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[A]">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[A]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التاني</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[B]">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[B]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التالت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[C]">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[C]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار الرابع</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="answer[D]">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[D]" required>
                                </div>
                            </div>`);
            } else if ($(this).val() == 'MCQ Single Answer') {
                $('#answer').html(`<div class="mb-3">
                                <label>الاختيار الاول</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="A" required>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[A]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التاني</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="B">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[B]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار التالت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="C">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[C]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>الاختيار الرابع</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="answer" value="D">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="options[D]" required>
                                </div>
                            </div>`);
            }else if ($(this).val() == 'True - False') {
                $('#answer').html(`<div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>True</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="check" value="A" required>
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
                                                <input type="radio" name="check" value="B">
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="false" readonly>
                                    </div>
                                </div>
                            </div>
`               )
            }else if ($(this).val() == 'Writing'){
                $('#answer').html(`<div class="form-group">
                                <label>الاجابة (تظهر بعد التصحيح)</label>
                                <textarea style="resize: none;" type="text" class="form-control" rows="8" name="solution" placeholder="الاجابة" required></textarea>
                            </div>`);
            }else{
                $('#answer').html('');
            }
        });
        $('form').submit(function (event) {
            if($('#type').val() == 'MCQ Multi-Answer'){
                if($('input[type=checkbox]:checked').length < 2){
                    event.preventDefault();
                }
            }
        })
    </script>

@endsection
