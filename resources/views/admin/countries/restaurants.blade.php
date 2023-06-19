@extends('admin.lteLayout.master')
@section('title')
    @lang('messages.restaurants')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">--}}
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>--}}
    {{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>--}}
    <!-- Theme style -->
    <style>
        .dropbtn {
            background-color: #04AA6D;
            color: white;
            padding: 10px;
            font-size: 10px;
            border: none;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 100px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 2px 3px;
            text-decoration: none;
            font-size: 10px;
            display: block;
        }

        .dropdown-content a:hover {background-color: #ddd;}

        .dropdown:hover .dropdown-content {display: block;}

        .dropdown:hover .dropbtn {background-color: #3e8e41;}
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @lang('messages.restaurants')
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
                            <a href="{{route('country_restaurants' , $country->id)}}">
                                @lang('messages.restaurants')
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
                                <th>@lang('messages.name')</th>
                                <th>@lang('messages.phone_number')</th>
                                <th>{{app()->getLocale() == 'ar' ? 'الحالة':'Status'}}</th>
                                {{--                                <th>@lang('messages.histories')</th>--}}
                                <th>@lang('messages.restaurant')</th>
                                <th>@lang('messages.products')</th>
                                <th>@lang('messages.views')</th>
                                <th>{{app()->getLocale() == 'ar' ? 'المشاهدات اليومية' : 'Daily Views'}}</th>
                                {{--                                <th>@lang('messages.package')</th>--}}
                                <th>{{app()->getLocale() == 'ar' ? 'ملاحظات' : 'Notes'}}</th>
                                <th> @lang('messages.created_at') </th>
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($restaurants as $restaurant)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        @if(app()->getLocale() == 'ar')
                                            {{$restaurant->name_ar}}
                                        @else
                                            {{$restaurant->name_en}}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $country = $restaurant->country->code;
                                            $check = substr($restaurant->phone_number, 0, 2) === '05';
                                            if ($check == true) {
                                                $phone = $country . ltrim($restaurant->phone_number, '0');
                                            } else {
                                                $phone = $country . $restaurant->phone_number;
                                            }
                                        @endphp
                                        @if($restaurant->phone_number != null)
                                            <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $phone }}">
                                                <i style="font-size:24px" class="fa">&#xf232;</i>
                                            </a>
                                            <a href="tel:{{$phone}}">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($restaurant->subscription != null)
                                            @if($restaurant->subscription->status == 'tentative')
                                                <a class="btn btn-info">@lang('messages.tentative')</a>
                                            @elseif($restaurant->subscription->status == 'tentative_finished')
                                                <a class="btn btn-danger">@lang('messages.tentative_finished')</a>
                                            @elseif($restaurant->subscription->status == 'active')
                                                <a class="btn btn-success"> @lang('messages.active') </a>
                                            @elseif($restaurant->subscription->status == 'finished')
                                                <a class="btn btn-danger"> @lang('messages.finished') </a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{url('/restaurants/' . $restaurant->name_barcode)}}" target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {{$restaurant->products->count()}}
                                    </td>
                                    <td>
                                        {{$restaurant->views}}
                                    </td>
                                    <td>
                                        <?php $daily_views = \App\Models\RestaurantView::whereRestaurantId($restaurant->id)->orderBy('id', 'desc')->first(); ?>
                                        @if($daily_views != null)
                                            {{$daily_views->views}}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    {{--                                    <td>--}}
                                    {{--                                        @if($restaurant->subscription != null and isset($restaurant->subscription->package->id))--}}
                                    {{--                                            {{app()->getLocale() == 'ar' ? $restaurant->subscription->package->name_ar : $restaurant->subscription->package->name_en}}--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </td>--}}
                                    <td>
                                        <a href="{{route('adminNote.index' , $restaurant->id)}}"
                                           class="btn btn-secondary">
                                            {{$restaurant->notes->count()}}
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info-{{$restaurant->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <div class="modal fade" id="modal-info-{{$restaurant->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-info">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            @lang('messages.created_at')
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            @lang('messages.date') :
                                                            {{$restaurant->created_at->format('Y-m-d')}}
                                                        </p>
                                                        <p>
                                                            {{app()->getLocale() == 'ar' ? 'الوقت': 'time'}} :
                                                            {{$restaurant->created_at->format('H:i:s')}}
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">
                                                            @lang('messages.close')
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-primary dropbtn" data-toggle="dropdown">
                                                @lang('messages.operations')
                                                <span class="caret"></span></button>
                                            <div class="dropdown-content" >
                                                @if($restaurant->archive == 'true')
                                                    <li>
                                                        <a class="btn btn-info"
                                                           href="{{route('ArchiveRestaurant' , [$restaurant->id , 'false'])}}"> @lang('messages.remove_archive')</a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="btn btn-secondary"
                                                           href="{{route('ArchiveRestaurant' , [$restaurant->id , 'true'])}}"> @lang('messages.archive')</a>
                                                    </li>
                                                @endif
                                                @if($restaurant->status != 'inComplete')
                                                    <li>
                                                        <a class="btn btn-success"
                                                           href="{{route('showRestaurant' , $restaurant->id)}}">
                                                            <i class="fa fa-eye"></i> @lang('messages.show')
                                                        </a>
                                                    </li>
                                                @endif
                                                @if($restaurant->status == 'inComplete')
                                                    <li>
                                                        <a class="btn btn-info"
                                                           href="{{route('inCompleteRestaurant' , $restaurant->id)}}">
                                                            <i class="fa fa-edit"></i>
                                                            {{app()->getLocale() == 'ar' ? 'أكمال التسجيل' : 'Complete Register'}}
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="btn btn-info"
                                                           href="{{route('editRestaurant' , $restaurant->id)}}">
                                                            <i class="fa fa-edit"></i> @lang('messages.edit')
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="btn btn-primary"
                                                       href="{{route('admin.restaurant_history' , $restaurant->id)}}">
                                                        <i class="fa fa-eye"></i>
                                                        @lang('messages.histories')
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="delete_city btn btn-danger" data="{{ $restaurant->id }}"
                                                       data_name="{{ $restaurant->name_ar }}">
                                                        <i class="fa fa-key"></i> @lang('messages.delete')
                                                    </a>
                                                </li>
                                            </div>
                                        </div>

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
    {{--    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>--}}
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
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_city', function () {
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
                }, function () {

                    {{--var url = '{{ route("imageProductRemove", ":id") }}';--}}

                        {{--url = url.replace(':id', id);--}}

                        window.location.href = "{{ url('/') }}" + "/admin/restaurants/delete/" + id;
                });
            });
        });
    </script>

@endsection
