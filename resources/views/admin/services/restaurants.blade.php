@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.service_restaurants')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <style>
        .btns a{
            margin: 5px 5px;
        }
    </style>
    <!-- Theme style -->
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.service_restaurants') : {{$service->name}}
                        @if($status == 'active')
                            (نشط)
                        @elseif($status == 'finished')
                            (منتهي)
                        @elseif($status == 'tentative')
                            (تجريبي نشط)
                        @elseif($status == 'tentative_finished')
                            (تجريبي منتهي)
                        @elseif($status == 'less_30_day')
                            (اقل من 30 يوم)
                        @endif
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.service.service_restaurants' , [$service->id , $status])}}">
                                @lang('messages.service_restaurants')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')

    <section class="content">
        <div class="btns">
            <a href="{{route('admin.service.service_restaurants' , [$service->id , 'active'])}}" class="btn btn-success"> النشط ( {{$statusCount['active']}} )</a>
            <a href="{{route('admin.service.service_restaurants' , [$service->id , 'finished'])}}" class="btn btn-danger"> المنتهي ( {{$statusCount['finished']}} )</a>
            <a href="{{route('admin.service.service_restaurants' , [$service->id , 'tentative'])}}" class="btn btn-primary"> التجريبي النشط ( {{$statusCount['tentative']}} )</a>
            <a href="{{route('admin.service.service_restaurants' , [$service->id , 'tentative_finished'])}}" class="btn btn-danger"> التجريبي المنتهي ( {{$statusCount['tentative_finished']}} )</a>
            <a href="{{route('admin.service.service_restaurants' , [$service->id , 'less_30_day'])}}" class="btn btn-secondary"> اقل من 30 يوم ( {{$statusCount['less_30_day']}} )</a>
            
        </div>
        {{-- <div class="row">
            <div class="col-sm-1">
                <a href="{{route('admin.service.service_restaurants' , [$service->id , 'active'])}}" class="btn btn-success"> النشط </a>
            </div>
            <div class="col-sm-1">
                <a href="{{route('admin.service.service_restaurants' , [$service->id , 'finished'])}}" class="btn btn-danger"> المنتهي </a>
            </div>
            <div class="col-sm-2">
                <a href="{{route('admin.service.service_restaurants' , [$service->id , 'tentative'])}}" class="btn btn-primary"> التجريبي النشط</a>
            </div>
            <div class="col-sm-2">
                <a href="{{route('admin.service.service_restaurants' , [$service->id , 'tentative_finished'])}}" class="btn btn-danger"> التجريبي المنتهي </a>

            </div>
            <div class="col-sm-2">
                <a href="{{route('admin.service.service_restaurants' , [$service->id , 'less_30_day'])}}" class="btn btn-secondary"> اقل من 30 يوم </a>
            </div>
        </div> --}}
        <br>
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
                                        <input type="checkbox" class="group-checkable"
                                               data-set="#sample_1 .checkboxes"/>
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th> @lang('messages.restaurant') </th>
                                <th> @lang('messages.branch') </th>
                                {{--                                <th> @lang('messages.service') </th>--}}
                                <th> {{app()->getLocale() == 'ar' ?'الحالة' : 'Status' }}</th>
                                <th> @lang('messages.subscription_date') </th>
                                <th> @lang('messages.end_date') </th>
                                <th> @lang('messages.orders_count') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($restaurant_services as $restaurant_service)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        @if($restaurant_service->restaurant)
                                            {{app()->getLocale() == 'ar' ? $restaurant_service->restaurant->name_ar : $restaurant_service->restaurant->name_en}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($restaurant_service->branch)
                                            {{app()->getLocale() == 'ar' ? $restaurant_service->branch->name_ar : $restaurant_service->branch->name_en}}
                                        @else
                                            {{\App\Models\Branch::whereRestaurantId($restaurant_service->restaurant_id)->where('main' , 'true')->first()->name_ar}}
                                        @endif
                                    </td>
                                    {{--                                    <td>--}}
                                    {{--                                        {{app()->getLocale() == 'ar' ? $restaurant_service->service->name : $restaurant_service->service->type}}--}}
                                    {{--                                    </td>--}}
                                    <td>
                                        @if($restaurant_service->status == 'active')
                                            <a class="btn btn-success" href="#">  @lang('messages.active') </a>
                                            @if($restaurant_service->end_at < \Carbon\Carbon::now()->addDays(30))
                                                <a href="{{route('admin.services_store.subscription' , $restaurant_service->id)}}"
                                                   style="margin-top: 20px;" class="btn btn-primary">
                                                    <i class="fa fa-user-edit"></i>
                                                    @lang('messages.renewSubscription')
                                                </a>
                                            @endif
                                        @elseif($restaurant_service->status == 'tentative')
                                            <a class="btn btn-success"
                                               href="#">  @lang('messages.tentative_active') </a>
                                            <br>
                                            <a href="{{route('admin.services_store.subscription' , $restaurant_service->id)}}"
                                               style="margin-top: 20px;" class="btn btn-primary">
                                                <i class="fa fa-user-edit"></i>
                                                @lang('messages.activate_subscription')
                                            </a>
                                        @elseif($restaurant_service->status == 'tentative_finished')
                                            <a class="btn btn-danger"
                                               href="#">  @lang('messages.tentative_finished') </a>
                                            <br>
                                            <a href="{{route('admin.services_store.subscription' , $restaurant_service->id)}}"
                                               style="margin-top: 20px;" class="btn btn-primary">
                                                <i class="fa fa-user-edit"></i>
                                                @lang('messages.activate_subscription')
                                            </a>
                                        @elseif($restaurant_service->status == 'finished')
                                            <a class="btn btn-secondary" href="#">  @lang('messages.finished') </a>
                                        @elseif($restaurant_service->status == 'canceled')
                                            <a class="btn btn-danger" href="#">  @lang('messages.canceled') </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($restaurant_service->paid_at)
                                            {{$restaurant_service->paid_at->format('Y-m-d')}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($restaurant_service->end_at)
                                            {{$restaurant_service->end_at->format('Y-m-d')}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($restaurant_service->service_id == 1)
                                            {{\App\Models\Reservation\ReservationOrder::whereRestaurantId($restaurant_service->restaurant_id)->count()}}
                                        @elseif($restaurant_service->service_id == 4)
                                            {{$restaurant_service->restaurant->foodics_orders}}
                                        @elseif($restaurant_service->service_id == 9)
                                            {{$restaurant_service->restaurant->whatsapp_orders}}
                                        @elseif($restaurant_service->service_id == 10)
                                            {{\App\Models\Order::whereRestaurantId($restaurant_service->restaurant_id)->whereBranchId($restaurant_service->branch_id)->whereIn('type',['previous' , 'takeaway' , 'delivery'])->count()}}
                                        @endif
                                    </td>
                                    <td>
                                        <a class="delete_data btn btn-danger" data="{{ $restaurant_service->id }}"
                                           data_name="{{app()->getLocale() == 'ar' ? $restaurant_service->restaurant->name_ar : $restaurant_service->restaurant->name_en}}">
                                            @lang('messages.delete')
                                        </a>

                                        <a class=" btn btn-success text-white" href="{{  route('ControlServiceSubscription' , $restaurant_service->id)}}">
                                             @lang('dashboard.expend_subscription')
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

                    window.location.href = "{{ url('/') }}" + "/admin/delete/service_restaurants/" + id;

                });

            });
        });
    </script>
@endsection
