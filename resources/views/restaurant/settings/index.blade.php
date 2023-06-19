@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.restaurant_orders_settings')
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
                    <h1>@lang('messages.restaurant_orders_settings')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant_setting.index')}}"></a>
                            @lang('messages.restaurant_orders_settings')
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
                    <a href="{{route('restaurant_setting.create')}}" class="btn btn-info">
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
                                <th> @lang('messages.branch') </th>
                                <th> @lang('messages.order_type') </th>
                                <th> @lang('messages.distance') </th>
                                <th> @lang('messages.receipt_payment') </th>
                                <th> @lang('messages.online_payment') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($settings as $setting)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($setting->branch->name_ar == null ? $setting->branch->name_en : $setting->branch->name_ar) : ($setting->branch->name_en == null ? $setting->branch->name_ar : $setting->branch->name_en)}}
                                    </td>
                                    <td>
                                        @if($setting->order_type == 'delivery')
                                            @lang('messages.delivery')
                                        @elseif($setting->order_type == 'takeaway')
                                            @lang('messages.takeaway')

                                        @elseif($setting->order_type == 'previous')
                                            @lang('messages.previous')
                                        @elseif($setting->order_type == 'whatsapp')
                                            @lang('dashboard.whatsapp_orders')
                                        @elseif($setting->order_type == 'easymenu')
                                            {{app()->getLocale() == 'ar' ? 'كاشير أيزي منيو' : 'EasyMenu Casher'}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$setting->distance}} @lang('messages.km')
                                    </td>
                                    <td>
                                        @if($setting->receipt_payment == 'true')
                                            @lang('messages.yes')
                                        @else
                                            @lang('messages.no')
                                        @endif
                                    </td>
                                    <td>
                                        @if($setting->online_payment == 'true')
                                            @lang('messages.yes')
                                        @else
                                            @lang('messages.no')
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info"
                                           href="{{route('restaurant_setting.edit' , $setting->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        @php
                                            $user = Auth::guard('restaurant')->user();
                                            $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                            ->wherePermissionId(7)
                                            ->first();
                                        @endphp
                                        @if($user->type == 'restaurant' or $deletePermission)
                                            <a class="delete_data btn btn-danger" data="{{ $setting->id }}"
                                               data_name="{{ app()->getLocale() == 'ar' ? ($setting->name_ar == null ? $setting->name_en : $setting->name_ar) : ($setting->name_en == null ? $setting->name_ar : $setting->name_en) }}">
                                                <i class="fa fa-key"></i> @lang('messages.delete')
                                            </a>
                                        @endif
                                        @if($setting->order_type == 'previous')
                                            <a class="btn btn-dark"
                                               href="{{route('order_setting_days.index' , $setting->id)}}">
                                                <i class="fa fa-calendar-day"></i> @lang('messages.order_periods')
                                            </a>
                                            <a class="btn btn-success"
                                               href="{{route('order_previous_days.index' , $setting->branch->id)}}">
                                                <i class="fa fa-calendar-day"></i> @lang('messages.menu_periods')
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

                    window.location.href = "{{ url('/') }}" + "/restaurant/restaurant_setting/delete/" + id;

                });

            });
        });
    </script>
@endsection

