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
                    <a href="{{route('restaurant.feedback.branch.create')}}" class="btn btn-info">
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
                                        <input type="checkbox" class="group-checkable"
                                               data-set="#sample_1 .checkboxes"/>
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th> @lang('dashboard.entry.name_ar') </th>
                                <th> @lang('dashboard.entry.name_en') </th>
                                <th style="max-width: 150px;"> @lang('dashboard.entry.link') </th>

                                <th> @lang('dashboard.entry.rate') </th>
                                <th> @lang('dashboard.entry.created_at') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0;?>
                            @foreach($branches as $index => $item)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="{{$item->id}}"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$item->name_ar}}</td>
                                    <td>{{$item->name_en}}</td>
                                    <td style="max-width: 150px;">
                                        <a href="{{$item->link}}" target="_blank">{{$item->link}}</a>
                                    </td>
                                    <td>
                                        @php

                                            $rate = \DB::select('SELECT sum(eat_rate) as eat_rate , SUM(place_rate) as place_rate, SUM(worker_rate) as worker_rate, SUM(service_rate) as service_rate, SUM(speed_rate) as speed_rate, SUM(reception_rate) as reception_rate , 
                                            count(*) as total_count 
                                            FROM restaurants_feedback WHERE branch_id = ' .$item->id );
                                            $rate = $rate[0];
                                            $item->rate_precent = $rate->total_count == 0 ? 0 : (($rate->eat_rate + $rate->place_rate + $rate->service_rate + $rate->worker_rate + $rate->speed_rate + $rate->reception_rate) * 100) / ($rate->total_count * 30);
                                            $item->rate_count = $rate->total_count;

                                        @endphp
                                        {{ trans('dashboard.rate') }} ({{$item->rate_count}})
                                        : {{number_format($item->rate_precent , 1)}}%
                                    </td>
                                    <td>{{date('Y-m-d h:i A' , strtotime($item->created_at))}}</td>

                                    <td>
                                        <a class="btn btn-info"
                                           href="{{route('restaurant.feedback.branch.edit' , $item->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        @php
                                            $user = Auth::guard('restaurant')->user();
                                            $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                            ->wherePermissionId(7)
                                            ->first();
                                        @endphp
                                        @if($user->type == 'restaurant' or $deletePermission)
                                            <a class="delete_data btn btn-danger" data="{{ $item->id }}"
                                               data_name="{{$item->name}}">
                                                <i class="fa fa-key"></i> @lang('messages.delete')
                                            </a>
                                        @endif

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
    {{$branches->links()}}

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
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
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
        $(document).ready(function () {
            $('body').on('click', '.delete_data', function () {
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
                }, function () {

                    window.location.href = "{{ url('/') }}" + "/restaurant/feedback/branch/delete/" + id;

                });

            });
        });
    </script>
@endsection

