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
                            <label for="first_name">الاسم المجموعة</label>
                            <input type="text" id="first_name" value="{{ $group->name }}" readonly class="form-control" name="name" placeholder="الاسم المجموعة" required>
                        </div>
                    </div>
                    <div>
                        <form method="post" action="{{ route('addUserGroup') }}">
                            <input hidden name="user_group_id" value="{{$group->id}}">
                            <div class="container page__heading-container">
                                <div class="page__heading d-flex align-items-center justify-content-between">
                                    <h1 class="m-0">اضافة طالب الي المجموعة</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-6 mb-3">
                                    <div class="form-group" >
                                        <label for="select01">المجموعة</label>
                                        <select id="select01" name="user_id" data-toggle="select" class="form-control">
                                            <option value="" >اختيار طالب</option>
                                            @foreach($studentsNotGroup as $student)
                                                <option value="{{$student->id}}">{{$student->Fullname}}</option>
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
                                <th>اسم الطالب</th>
                                <th>رقم الهاتف</th>
                                <th>اسم المستخدم</th>
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
                                    <td>{{$student->username}}</td>
                                    <td>{{$student->email}}</td>
                                    <td>
                                        <a href="{{ route('student.show', $student->id) }}"><button type="button" class="btn btn-primary">عرض</button></a>
                                        <a href="{{ route('student.edit', $student->id) }}"><button type="button" class="btn btn-secondary">تعديل</button></a>
                                        <button id="{{ $student->id }}" name="{{$student->Fullname}}" data-toggle="modal" data-target="#modal-standard" type="button" class="btn btn-danger delete-item-table">حذفه من المجموعة</button>
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
                $(this).find('p').text(`انت تقوم بحذف الطالب ${event.relatedTarget.name} من المجموعة `)
                $(this).find('form').attr('action', $(event.relatedTarget).attr('action'));
                $(this).find('#user_id').attr('value', $(event.relatedTarget).attr('id'));
            })

        });
    </script>
    <div id="modal-standard" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('removeUserGroup')}}">
                    <input hidden name="user_group_id" value="{{$group->id}}">
                    <input hidden name="user_id" id="user_id">
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

