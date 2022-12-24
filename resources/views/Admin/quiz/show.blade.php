@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
@endsection

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
                            <label for="first_name">الاسم الكويز</label>
                            <input type="text" id="name" readonly value="{{ $quiz->title }}" class="form-control" name="title" placeholder="اسم الكويز" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="first_name">وصف الكويز</label>
                            <input type="text" readonly value="{{ $quiz->description  }}" class="form-control" name="description" placeholder="وصف الكويز" >
                        </div>
                    </div>
                    <div>
                        <form method="post" action="{{ route('addQuestionQuiz') }}">
                            <input hidden name="quiz_id" value="{{$quiz->id}}">
                            <div class="container page__heading-container">
                                <div class="page__heading d-flex align-items-center justify-content-between">
                                    <h1 class="m-0">اضافة السؤال الي الكويز</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-6 mb-3">
                                    <div class="form-group" >
                                        <label for="select01">اختيار السؤال</label>
                                        <select id="question_select" class="form-control selectpicker" name="question_id" data-live-search="true" required>
                                            <option value="" >اختيار السؤال</option>
                                            @foreach($questionsNotQuizzes as $question)
                                                <option data-tokens="{{$question->year . ' - ' . $question->question}}" value="{{$question->id}}">{{$question->year . ' - ' . $question->question}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="align-items-center col-4 col-md-6 d-flex mb-3">
                                    <button class="btn btn-primary" type="submit">إضافة</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive border-bottom">
                        <table id="table" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>السؤل</th>
                                <th>النوع</th>
                                <th>السنة</th>
                                <th>إجراء</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quiz->questions as $question)
                                <tr>
                                    <td>{{$question->id}}</td>
                                    <td>{{$question->question}}</td>
                                    <td>{{$question->type}}</td>
                                    <td>{{$question->year}}</td>
                                    <td>
                                        <a href="{{ route('question.show', $question->id) }}"><button type="button" class="btn btn-primary">عرض</button></a>
                                        <a href="{{ route('question.edit', $question->id) }}"><button type="button" class="btn btn-secondary">تعديل</button></a>
                                        <button id="{{ $question->id}}" name="{{$question->question}}" data-toggle="modal" data-target="#modal-standard" type="button" class="btn btn-danger delete-item-table">حذفه من الكويز</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            let table = $('#table').DataTable({
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: $('#table tr:first-child td').length - 1 }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json'
                }
            });
            $('#modal-standard').on('show.bs.modal', function (event) {
                $(this).find('p').text(`انت تقوم بحذف السؤال ${event.relatedTarget.name} من الكويز `)
                $(this).find('form').attr('action', $(event.relatedTarget).attr('action'));
                $(this).find('#question_id').attr('value', $(event.relatedTarget).attr('id'));
            })

        });
        $(function() {
            $('#question_select').selectpicker();
        });
    </script>
    <div id="modal-standard" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('removeQuestionQuiz')}}">
                    <input hidden name="quiz_id" value="{{$quiz->id}}">
                    <input hidden name="question_id" id="question_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-standard-title">تاكيد الحذف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">تراجع</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

