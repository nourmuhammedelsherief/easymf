@extends('marketer.lteLayout.master')

@section('title')
    @lang('messages.confirmed_operations')
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
                    <h1>@lang('messages.confirmed_operations')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/marketer/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('confirmed_operations')}}"></a>
                            @lang('messages.confirmed_operations')
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
                                <th> @lang('messages.seller_code') </th>
                                <th> @lang('messages.restaurant') </th>
                                <th> @lang('messages.commission') </th>
                                <th> @lang('messages.package') </th>
                                <th> @lang('messages.register_date') </th>
{{--                                <th> @lang('messages.operations') </th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($operations as $operation)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{$operation->seller_code->seller_name}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? $operation->subscription->restaurant->name_ar : $operation->subscription->restaurant->name_en}}
                                    </td>
                                    <td>
                                        {{$operation->amount}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? $operation->subscription->package->name_ar : $operation->subscription->package->name_en}}
                                    </td>
                                    <td>
                                        {{$operation->created_at->format('Y-m-d')}}
                                    </td>
{{--                                    <td>--}}

{{--                                        <a class="btn btn-info" href="{{route('modifiers.edit' , $operation->id)}}">--}}
{{--                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')--}}
{{--                                        </a>--}}
{{--                                        <a class="delete_data btn btn-danger" data="{{ $operation->id }}" data_name="{{ app()->getLocale() == 'ar' ? ($operation->name_ar == null ? $operation->name_en : $operation->name_ar) : ($operation->name_en == null ? $operation->name_ar : $operation->name_en) }}" >--}}
{{--                                            <i class="fa fa-key"></i> @lang('messages.delete')--}}
{{--                                        </a>--}}

{{--                                    </td>--}}
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
    {{$operations->links()}}

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

                    window.location.href = "{{ url('/') }}" + "/restaurant/modifiers/delete/" + id;

                });

            });
        });
    </script>
@endsection

