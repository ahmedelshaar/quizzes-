@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid page__heading-container">
        <div
            class="page__heading d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-lg-between text-center text-lg-left">
            <h1 class="m-lg-0">{{ $title }}</h1>
        </div>
        @include('inc.alert.success')
        @include('inc.alert.error')
    </div>


    <div class="container-fluid page__container">
        <div class="table-responsive border-bottom">

            <table id="table" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>الطالب</th>
                    <th>السؤال</th>
                    <th>الرسالة</th>
                    <th>إجراء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($feedbacks as $feedback)
                <tr>
                    <td>{{$feedback->id}}</td>
                    <td>{{$feedback->student->FullName}}</td>
                    <td>{{$feedback->question->question}}</td>
                    <td>{{$feedback->body}}</td>
                    <td>
                        <a class="mb-3 d-inline-block" href="{{ route('student.show', $feedback->id) }}"><button type="button" class="btn btn-primary">عرض الطالب</button></a>
                        <a class="mb-3 d-inline-block" href="{{ route('question.edit', $feedback->question->id) }}"><button type="button" class="btn btn-secondary">تعديل السؤال</button></a>
                        <a class="mb-3 d-inline-block"><button action="{{ route('feedback.destroy', $feedback->id) }}" name="{{$feedback->body}}" data-toggle="modal" data-target="#modal-standard" type="button" class="btn btn-danger">حذف</button></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('scripts')
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
            $(this).find('p').text(`انت تقوم بحذف الملاحظة: ${event.relatedTarget.name}`)
            $(this).find('form').attr('action', $(event.relatedTarget).attr('action'));
        })

    });
</script>
<div id="modal-standard" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" >
                {{ method_field('delete') }}
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
