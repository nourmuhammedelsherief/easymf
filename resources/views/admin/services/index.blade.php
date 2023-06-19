@extends('admin.lteLayout.master')

@section('title')
    @lang('dashboard.our_services')
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
                    <h1>@lang('dashboard.our_services')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.service.index')}}"></a>
                            @lang('dashboard.our_services')
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

                                <th>{{ trans('messages.photo') }}</th>
                                <th>@lang('messages.name')</th>
                                <th>@lang('messages.price')</th>
                                <th>@lang('dashboard.entry.status')</th>
                                <th>@lang('dashboard.countries')</th>
                                <th> @lang('messages.restaurants') </th>
                                {{--                                <th> @lang('messages.orders_count') </th>--}}
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($services as $item)
                                <tr class="odd gradeX">

                                    <td>
                                        <div class="image-preview" style="width: 100px;height:100px;">
                                            <img src="{{asset($item->image_path)}}" alt="" style="width:100%;height: 100%;">
                                        </div>
                                    </td>
                                    <td>
                                        {{$item->name}}
                                    </td>
                                    <td> {{$item->price}} </td>
                                    <td>
                                        @if($item->status == 'true')
                                            <span class="badge badge-success">{{trans('dashboard.yes')}}</span>
                                        @else
                                            <span class="badge badge-secondary">{{trans('dashboard.no')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info" href="{{route('admin.service.country.index' , $item->id)}}">{{$item->prices_count}}
                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('admin.service.service_restaurants' , [$item->id , 'active'])}}">
                                            {{App\Models\ServiceSubscription::with('restaurant')
                                              ->whereHas('restaurant' , function ($q){
                                                  $q->where('archive' , 'false');
                                                  $q->with('subscription');
                                                  $q->whereHas('subscription' , function ($d){
                                                      $d->whereIn('status' , ['active' , 'tentative']);
                                                  });
                                              })
                                              ->whereIn('status' ,[ 'active' , 'tentative'])
                                              ->whereServiceId($item->id)->count()}}
                                        </a>
                                    </td>
                                    {{--                                    <td>--}}
                                    {{--                                        @if($item->id == 1)--}}
                                    {{--                                            {{\App\Models\Reservation\ReservationOrder::count()}}--}}
                                    {{--                                        @elseif($item->id == 4)--}}
                                    {{--                                            {{\App\Models\Restaurant::sum('foodics_orders')}}--}}
                                    {{--                                        @elseif($item->id == 5)--}}
                                    {{--                                            {{\App\Models\Order::whereType('takeaway')->count()}}--}}
                                    {{--                                        @elseif($item->id == 6)--}}
                                    {{--                                            {{\App\Models\Order::whereType('delivery')->count()}}--}}
                                    {{--                                        @elseif($item->id == 7)--}}
                                    {{--                                            {{\App\Models\Order::whereType('previous')->count()}}--}}
                                    {{--                                        @elseif($item->id ==8)--}}
                                    {{--                                            {{\App\Models\TableOrder::count()}}--}}
                                    {{--                                        @elseif($item->id == 9)--}}
                                    {{--                                            {{\App\Models\Restaurant::sum('whatsapp_orders')}}--}}
                                    {{--                                        @elseif($item->id == 10)--}}
                                    {{--                                            {{\App\Models\Order::whereIn('type',['previous' , 'takeaway' , 'delivery'])->count()}}--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </td>--}}
                                    <td>
                                        <a class="btn btn-success" href="{{route('admin.service.edit' , $item->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
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

                        window.location.href = "{{ url('/') }}" + "/admin/packages/delete/"+id;


                });

            });

        });
    </script>

@endsection
