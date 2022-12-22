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
                    <form method="post" action="{{ route('question.store') }}">
                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label>السؤال</label>
                                <textarea style="resize: none;" type="text" value="{{ old('question') }}" class="form-control" rows="8" name="question" placeholder="السؤال" required></textarea>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="select01">السنة</label>
                                    <select id="select01" name="year" data-toggle="select" class="form-control" required>
                                        <option value="" @if(!old('year')) selected @endif>اختيار السنة</option>
                                        @foreach($years as $year)
                                            <option value="{{$year}}"
                                                    @if(old('year') == $year)
                                                    selected
                                                @endif
                                            >{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>توضيح (hint)</label>
                                    <input type="text" value="{{ old('hint') }}" class="form-control" name="hint" placeholder="hint" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" >صورة</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image" required>
                                        <label class="custom-file-label" >Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="select01">نوع السؤال</label>
                                <select id="select01" name="year" data-toggle="select" class="form-control" required>
                                    <option value="" @if(!old('type')) selected @endif>نوع السؤال</option>
                                    @foreach($types as $type)
                                        <option value="{{$type}}"
                                                @if(old('type') == $type)
                                                selected
                                            @endif
                                        >{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


