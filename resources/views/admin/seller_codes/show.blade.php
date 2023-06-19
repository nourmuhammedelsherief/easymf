@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.code_restaurants') ({{$seller_code->seller_name}})
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
                    <h1>@lang('messages.code_restaurants') ({{$seller_code->seller_name}})</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('seller_codes.index' , $seller_code->marketer_id)}}"></a>
                            @lang('messages.code_restaurants') ({{$seller_code->seller_name}})
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
                                <th> @lang('messages.name') </th>
                                <th> @lang('messages.phone_number') </th>
                                <th> @lang('messages.package') </th>
                                <th> @lang('messages.total_amount') </th>
                                <th> @lang('messages.discount') </th>
                                <th> @lang('messages.commission') </th>
                                <th> @lang('messages.register_date') </th>
                                <th> @lang('messages.end_subscription') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($seller_code->operations->where('status' , 'done') as $seller_code)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{app()->getLocale() == 'ar' ? $seller_code->subscription->restaurant->name_ar : $seller_code->subscription->restaurant->name_en}} </td>
                                    <td> <a href="tel:{{$seller_code->subscription->restaurant->phone_number}}">{{$seller_code->subscription->restaurant->phone_number}}</a> </td>
                                    <td> {{app()->getLocale() == 'ar' ? $seller_code->subscription->package->name_ar : $seller_code->subscription->package->name_en}} </td>
                                    <td> {{$seller_code->subscription->price}}  {{app()->getLocale() == 'ar' ? $seller_code->subscription->restaurant->country->currency_ar : $seller_code->subscription->restaurant->country->currency_en}}</td>
                                    <td> {{$seller_code->subscription->package->price - $seller_code->subscription->price}}  {{app()->getLocale() == 'ar' ? $seller_code->subscription->restaurant->country->currency_ar : $seller_code->subscription->restaurant->country->currency_en}}</td>
                                    <td> {{$seller_code->amount}}  {{app()->getLocale() == 'ar' ? $seller_code->subscription->restaurant->country->currency_ar : $seller_code->subscription->restaurant->country->currency_en}}</td>
                                    <td> {{$seller_code->subscription->created_at->format('Y-m-d')}}  </td>
                                    <td> {{$seller_code->subscription->end_at->format('Y-m-d')}}  </td>
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
                                $tc = \App\Models\MarketerOperation::whereMarketerId($seller_code->marketer->id)->where('status' , 'done')->sum('amount');
                                $tt = \App\Models\MarketerTransfer::whereMarketerId($seller_code->marketer->id)->sum('amount');
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
                    <!-- /.col -->
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
{{--    {{$seller_codes->links()}}--}}
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

                    window.location.href = "{{ url('/') }}" + "/admin/seller_codes/delete/" + id;

                });

            });
        });
    </script>
@endsection
