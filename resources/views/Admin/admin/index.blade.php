@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid page__heading-container">
        <div
            class="page__heading d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-lg-between text-center text-lg-left">
            <h1 class="m-lg-0">{{ $title }}</h1>
            <a href="{{ route('admin.create') }}" class="btn btn-success ml-lg-3">اضافة مدير جديد <i
                    class="material-icons">add</i></a>
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
                    <th>اسم الطالب</th>
                    <th>رقم الهاتف</th>
                    <th>ايميل</th>
                    <th>إجراء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{$student->id}}</td>
                    <td>{{$student->FullName}}</td>
                    <td>{{$student->mobile ?? '--'}}</td>
                    <td>{{$student->email}}</td>
                    <td>
                        <a href="{{ route('admin.show', $student->id) }}"><button type="button" class="btn btn-primary">عرض</button></a>
                        <a href="{{ route('admin.edit', $student->id) }}"><button type="button" class="btn btn-secondary">تعديل</button></a>
                        <button action="{{ route('admin.destroy', $student->id) }}" name="{{$student->FullName}}" data-toggle="modal" data-target="#modal-standard" type="button" class="btn btn-danger delete-item-table">حذف</button>
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
            $(this).find('p').text(`انت تقوم بحذف المدير: ${event.relatedTarget.name}`)
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
