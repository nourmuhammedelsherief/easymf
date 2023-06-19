@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.seller_codes')
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
                    <h1>@lang('messages.seller_codes')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('seller_codes.index' , $marketer->id)}}"></a>
                            @lang('messages.seller_codes')
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
                    <a href="{{route('seller_codes.create')}}" class="btn btn-info">
                        <i class="fa fa-plus"></i>
                        @lang('messages.add_new')
                    </a>
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
                                <th> @lang('messages.marketer') </th>
                                <th> @lang('dashboard.used_type') </th>
                                
                                <th> @lang('messages.seller_name') </th>
                                <th> @lang('dashboard.url') </th>
                                <th> @lang('messages.percentage') </th>
                                <th> @lang('messages.code_percentage') </th>
                                <th> @lang('messages.restaurants_counts') </th>
                                <th> @lang('messages.activity') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($seller_codes as $seller_code)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$seller_code->marketer->name}} </td>
                                    <td>
                                        @if($seller_code->used_type == 'code')
                                            <span class="badge badge-primary">{{ trans('dashboard.code') }}</span>
                                        @else 
                                            <span class="badge badge-success">{{ trans('dashboard.url') }}</span>
                                        @endif
                                    </td>
                                    <td> {{$seller_code->seller_name}} </td>
                                    <td> 
                                        @if($seller_code->used_type == 'url')
                                        <a href="{{url('restaurants-registration/' . $seller_code->custom_url)}}" target="__blank">{{$seller_code->custom_url}}</a>    
                                        @endif
                                    </td>
                                    <td> {{$seller_code->percentage}} % </td>
                                    <td> {{$seller_code->code_percentage}} % </td>
                                    <td>
                                        <a href="{{route('showSellerCodeOps' , $seller_code->id)}}" class="btn btn-info">{{$seller_code->operations->where('status' , 'done')->count()}}</a>
                                    </td>
                                    <td>
                                        <span class="custom-switch {{$seller_code->active == 'true' ? 'on' : 'off'}}"
                                            data-url_on="{{route('activateSellerCode' , [$seller_code->id , 'false'])}}"
                                            data-url_off="{{route('activateSellerCode' , [$seller_code->id , 'true'])}}">
                                          <span class="text">On</span>
                                          <span class="move"></span>
                                      </span>
                                        {{-- <span class="custom-switch {{$seller_code->active == 'true' ? 'on' : 'off'}}" data-url_on="{{route('activateSellerCode' , [$seller_code->id , 'false'])}}" data-url_off="{{route('activateSellerCode' , [$seller_code->id , 'true'])}}">
                                            <span class="text">On</span>
                                            <span class="move"></span>
                                        </span> --}}
                               
                                    </td>
                                    <td>
                                        <a class="btn btn-info" href="{{route('seller_codes.edit' , $seller_code->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>

                                        <a class="delete_data btn btn-danger" data="{{ $seller_code->id }}" data_name="{{ $seller_code->seller_name }}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"> @lang('messages.marketer_operations') </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <?php
                                $tc = \App\Models\MarketerOperation::whereMarketerId($marketer->id)->where('status' , 'done')->sum('amount');
                                $tt = \App\Models\MarketerTransfer::whereMarketerId($marketer->id)->sum('amount');
                                ?>
                                <h3> @lang('messages.total_commission') :  {{$tc}}</h3>
                                <h3> @lang('messages.total_transfers') :  {{$tt}}</h3>
                                <h3> @lang('messages.restBalance') :  {{ $tc-$tt }}</h3>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- /.card -->
                    </div>

                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
    {{$seller_codes->links()}}
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

                    window.location.href = "{{ url('/') }}" + "/admin/seller_code/delete/" + id;

                });

            });
        });
    </script>
@endsection
