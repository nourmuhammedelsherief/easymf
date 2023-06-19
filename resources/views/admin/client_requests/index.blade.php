@extends('admin.lteLayout.master')

@section('title')
    @lang('dashboard.client_requests')
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
                        @lang('dashboard.client_requests') @if($isArchived == true)@lang('messages.archived')@endif
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        @if($isArchived == false)
                            <li class="breadcrumb-item active">
                                <a href="{{route('admin.client_request.index')}}"></a>
                                @lang('dashboard.client_requests')
                            </li>
                        @else 
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.client_request.index')}}">@lang('dashboard.client_requests')</a>
                                
                            </li>

                            <li class="breadcrumb-item active">
                                <a href="{{route('admin.client_request.index')}}"></a>
                                @lang('messages.archived')
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <h3>
                    @if($isArchived == true)
                        <a href="{{route('admin.client_request.index')}}" class="btn btn-primary">
                            {{-- <i class="fa fa-plus"></i> --}}
                            @lang('dashboard.client_requests') @if($isArchived == true)@lang('messages.unarchived')@endif
                        </a>
                    @else 

                        <a href="{{route('admin.client_request.create')}}" class="btn btn-info">
                            <i class="fa fa-plus"></i>
                            @lang('messages.add_new')
                        </a>

                        <a href="{{route('admin.client_request.index')}}?is_archived=1" class="btn btn-primary">
                            {{-- <i class="fa fa-plus"></i> --}}
                            @lang('dashboard.client_requests') @if($isArchived == false)@lang('messages.archived')@endif
                        </a>
                    @endif
                </h3>
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>
                                <th>{{ trans('dashboard.request_number') }}</th>
                                <th>@lang('messages.name')</th>
                                <th>@lang('dashboard.entry.phone')</th>
                                <th>@lang('dashboard.income')</th>
                                
                                <th>@lang('dashboard.note_count')</th>
                                <th>@lang('dashboard.entry.created_at')</th>
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($requests as $request)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo $request->id; ?></td>
                                    <td> {{$request->name}} </td>
                                    <td> {{$request->phone}} </td>
                                    <td>
                                       {{$request->income}}
                                    </td>
                                    
                                    <td>
                                        <a href="{{route('admin.client_request.note.index' , $request->id)}}" class="btn btn-success"> {{$request->notes_count}} </a>
                                    </td>
                                    <td>
                                        {{date('Y-m-d h:i A' , strtotime($request->created_at))}}
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('admin.client_request.show' , $request->id)}}">
                                            <i class="fa fa-eye"></i> @lang('dashboard.view')
                                        </a>
                                        <a class="btn btn-info" href="{{route('admin.client_request.edit' , $request->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        @if($isArchived == false)
                                            <a class="btn btn-warning" href="{{route('admin.client_request.archived' , $request->id)}}?status=1">
                                                <i class="fa fa-user-edit"></i> @lang('messages.archived')
                                            </a>
                                        @else 
                                            <a class="btn btn-warning" href="{{route('admin.client_request.archived' , $request->id)}}?status=0">
                                                <i class="fa fa-user-edit"></i> @lang('messages.unarchived')
                                            </a>
                                        @endif
                                        <a class="delete_city btn btn-danger" data="{{ $request->id }}" data_name="{{ $request->name }}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>


@endsection

@section('scripts')
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                lengthMenu: [
                    [10, 25, 50 , 100, -1],
                    [10, 25, 50,  100,'All'],
                ],
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_city', function() {
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

                        window.location.href = "{{ url('/') }}" + "/admin/client_request/delete/"+id;


                });

            });

        });
    </script>

@endsection
