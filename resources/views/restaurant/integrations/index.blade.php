@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.tab_2')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
    <style>
        img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            width: 150px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @lang('dashboard.tab_2')
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('offers.index')}}"></a>
                            @lang('dashboard.tab_2')
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="row">
            @include('flash::message')
            <div class="col-12">
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th> @lang('dashboard.image') </th>
                                <th> @lang('dashboard.service_name') </th>
                                <th> @lang('dashboard.branch') </th>
                                <th> @lang('dashboard.entry.time') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0;?>
                            @foreach($subscriptionServices as $index => $item)
                                <tr class="odd gradeX">
                                    <td>
                                        <div class="image-p">
                                            <img src="{{asset($item->service->image_path)}}" alt="">
                                        </div>
                                    </td>
                                    <td>{{$item->service->name}}</td>
                                    <td>{{isset($item->branch->id) ? $item->branch->name : ''}}</td>
                                    <td>
                                        @if($item and $item->end_at <= now()->addDays(30))
                                                <?php
                                                $ticketTime = strtotime($item->end_at);

                                                // This difference is in seconds.
                                                $difference = $ticketTime - time();
                                                ?>
                                            
                                                {{app()->getLocale() == 'ar' ? 'متبقي' . round($difference / 86400) . 'يوم': 'stay' .round($difference / 86400). 'day' }}
                                                
                                        @else 
                                        {{date('Y-m-d' , strtotime($item->end_at))}} {{$item->id}}
                                        @endif
                                      
                                    </td>
                                   
                                    {{-- <td>{{date('Y-m-d h:i A' , strtotime($item->created_at))}}</td> --}}

                                    <td>
                                        <a class="btn btn-secondary"
                                           href="{{route('print_service_invoice' , $item->id)}}">
                                            @lang('messages.show_invoice')
                                        </a>
                                        @php
                                            $state = generateApiToken($restaurant->id, 10);
                                            $restaurant->update([
                                                'foodics_state' => $state,
                                            ]);
                                            $foodics_url = 'https://console.foodics.com/authorize?client_id=94a7eeac-5881-4b19-ae6a-024debd9ac05&state=' . $state;

                                            $myFoodicsService = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                                                ->where('status' , 'active')
                                                ->where('service_id' , 4)
                                                ->first();
                                        @endphp
                                        @if($item->service_id == 4)
                                                @if($item and $restaurant->foodics_access_token == null and $restaurant->foodics_status == 'true')
                                             
                                                <a class="btn btn-primary" href="{{$foodics_url}}">
                                                    {{app()->getLocale() == 'ar' ? 'التكامل مع فوودكس' : 'Foodics Integration'}}
                                                </a>
                                                @elseif($item and $restaurant->foodics_access_token != null && $restaurant->foodics_status == 'true')
                                                    
                                                    <a class="btn btn-success" href="{{route('pull_menu' , $restaurant->id)}}">
                                                        سحب المنيو
                                                    </a>
                                                @endif
                                        {{-- loyalty points --}}
                                        @elseif($item->service_id == 11)
                                            
                                                <a class="btn btn-primary"
                                                href="{{route('restaurant.loyalty_point.setting')}}">
                                                    {{app()->getLocale() == 'ar' ? 'الأعدادات' : 'Settings'}}
                                                </a>
                                            


                                        {{-- loyalty points --}}
                                        @elseif($item->service_id == 9)
                                            
                                                <a class="btn btn-primary" href="{{route('restaurant_setting.index')}}">
                                                    {{app()->getLocale() == 'ar' ? 'الأعدادات' : 'Settings'}}
                                                </a>
                                            

                                        @elseif($item->service_id == 10)
                                           
                                                <a class="btn btn-primary" href="{{route('restaurant_setting.index')}}">
                                                    {{app()->getLocale() == 'ar' ? 'الأعدادات' : 'Settings'}}
                                                </a>
                                            
                                        @endif
                                        


                                        @if($item and $item->end_at <= now()->addDays(30))
                                           
                                            <a href="{{route('restaurant.services_store.subscription' , $item->service_id)}}"
                                            style="" class="btn btn-primary">
                                                <i class="fa fa-user-edit"></i>
                                                {{$item->status == 'tentative' ? trans('messages.activate_subscription') : trans('messages.renewSubscription')}}
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

                    window.location.href = "{{ url('/') }}" + "/restaurant/offers/delete/" + id;

                });

            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('body').on('click', '.delete_data', function () {
                var id = $(this).attr('data');
                var swal_text = '{{trans('messages.delete')}} ' + $(this).attr('data_name');
                var warrning = '\n' + 'تحذير !! سيتم حذف المنيو كاملا في حالة الغاء الربط';
                var swal_title = "{{trans('messages.deleteSure')}}";

                swal({
                    title: swal_title,
                    text: swal_text + warrning,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "{{trans('messages.sure')}}",
                    cancelButtonText: "{{trans('messages.close')}}"
                }, function () {

                    window.location.href = "{{ url('/') }}" + "/remove_foodics_integration/" + id;

                });

            });
        });
    </script>
@endsection

