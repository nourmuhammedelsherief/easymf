@extends('admin.lteLayout.master')

@section('title')
    @lang('dashboard.view') @lang('dashboard.client_request')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @lang('dashboard.view') @lang('dashboard.client_request')
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.client_request.index')}}">
                                @lang('dashboard.client_request')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title" style="float: right;">@lang('dashboard.view') @lang('dashboard.client_request') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" disabled action="{{route('admin.client_request.store' )}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name') </label>
                                    <input name="name" type="text" class="form-control" value="{{$request->name}}" placeholder="@lang('messages.name')" required disabled>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.phone') </label>
                                    <input name="phone" type="text" class="form-control" value="{{$request->phone}}" disabled placeholder="@lang('dashboard.entry.phone')" required>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('phone') }}</strong>
                                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.income') </label>
                                    <input name="income" type="text" class="form-control" value="{{$request->income}}" placeholder="@lang('dashboard.income')" disabled required>
                                    @if ($errors->has('income'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('income') }}</strong>
                                                    </span>
                                    @endif
                                </div>
                              
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.client_request_description') </label>
                                    <textarea name="description" disabled type="text" class="form-control"  placeholder="@lang('dashboard.client_request_description')" rows="10" required>{{$request->description}}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                                    </span>
                                    @endif
                                </div>




                                <div class="notes-content">
                                    <h2 class="text-center mt-5">{{ trans('dashboard.notes') }}</h2>
                                    <h3>
                                        <a href="{{route('admin.client_request.note.create' , $request->id)}}" class="btn btn-info">
                                            <i class="fa fa-plus"></i>
                                            @lang('messages.add_new')
                                        </a>
                                    </h3>
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>
                                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th></th>
                                            <th>@lang('dashboard.description')</th>
                                            <th>@lang('dashboard.entry.created_at')</th>
                                            <th>@lang('messages.operations')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=0 ?>
                                        @foreach($request->notes as $note)
                                            <tr class="odd gradeX">
                                                <td>
                                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                        <input type="checkbox" class="checkboxes" value="1" />
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td><?php echo ++$i ?></td>
                                                <td> {{$note->description}} </td>
                                                <td> {{date('Y-m-d h:i A' , strtotime($note->created_at))}} </td>
                                               
                                                <td>
                                                    <a class="btn btn-info" href="{{route('admin.client_request.note.edit' , [$request->id , $note->id])}}">
                                                        <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                                    </a>
                                                    <a class="delete_note btn btn-danger" data="{{ $note->id }}" data_name="{{ $note->description }}" >
                                                        <i class="fa fa-key"></i> @lang('messages.delete')
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->


                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        $(function () {
            $("#example2").DataTable({
                lengthMenu: [
                    [10, 25, 50 , 100, -1],
                    [10, 25, 50,  100,'All'],
                ],
            });
            
        });
    </script>
    <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_note', function() {
                var id = $(this).attr('data');

                var swal_text = 'حذف ' + $(this).attr('data_name') + '؟';
                var swal_title = 'هل أنت متأكد من الحذف ؟';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق",
                    closeOnConfirm: false
                }, function() {

                    {{--var url = '{{ route("imageProductRemove", ":id") }}';--}}

                        {{--url = url.replace(':id', id);--}}

                        window.location.href = "{{ url('/') }}" + "/admin/client_request/{{$request->id}}/note/delete/"+id;


                });

            });

        });
    </script>

@endsection

