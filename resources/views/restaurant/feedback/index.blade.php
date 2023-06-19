@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.restaurant_feedback')
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
                    <h1>@lang('dashboard.restaurant_feedback')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        
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
                                <th></th>
                                <th> @lang('dashboard.entry.name') </th>
                                <th> @lang('dashboard.entry.mobile') </th>
                                <th> @lang('dashboard.branch') </th>
                                <th> @lang('dashboard.entry.message') </th>
                                
                                <th> @lang('dashboard.entry.rate') </th>
                                <th> @lang('dashboard.entry.created_at') </th>
                                {{-- <th> @lang('messages.operations') </th> --}}
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0; ?>
                            @foreach($feedbacks as $index => $item)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="{{$item->id}}" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$item->name}}</td>
									<td>{{$item->mobile}}</td>
                                    <td>
                                        {{isset($item->branch->id) ? $item->branch->name : ''}}
                                    </td>
									<td>{{$item->message}}</td>
									<td>
										{!! $item->getAllRateHtml() !!}
									</td>
									<td>{{date('Y-m-d h:i A' , strtotime($item->created_at))}}</td>
               
                                    {{-- <td>

                                        <a class="delete_data btn btn-danger" data="{{ $item->id }}" data_name="{{ $item->name }}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>

                                    </td> --}}
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
    {{$feedbacks->links()}}

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
        $( document ).ready(function () {
            $('body').on('click', '.delete_data', function() {
                var id = $(this).attr('data');
                var swal_text = '{{trans('messages.delete')}} ' + $(this).attr('data_name');
                var swal_title = "{{trans('messages.deleteSure')}}";

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "{{trans('messages.sure')}}",
                    cancelButtonText: "{{trans('messages.close')}}"
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/restaurant/menu_categories/delete/" + id;

                });

            });
        });
    </script>
@endsection

