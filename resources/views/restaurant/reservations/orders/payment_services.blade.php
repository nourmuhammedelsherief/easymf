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
    @include('flash::message')

    <section class="content">
        <div class="row">
            @include('flash::message')
            <div class="col-12">
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            @php
                            
                            
                            $listAll = [
                                [
                                    'id' => 1 , 
                                    'name' =>trans('dashboard.bank_transfer')  , 
                                    'url' => url('restaurant/banks') , 
                                    'status' => $restaurant->enable_reservation_bank == 'true' ? true : false
                                ] ,
                                [
                                    'id' => 2 , 
                                    'name' =>trans('dashboard.online_payment') , 
                                    'url' => route('myfatoora_token') , 
                                    'status' => $restaurant->enable_reservation_online_pay == 'true' ? true : false
                                ] ,
                                [
                                    'id' => 3 , 
                                    'name' => trans('dashboard.cash_on_delivery') , 
                                    'url' => route('resetaurant.reservation.cash-settings') , 
                                    'status' => $restaurant->enable_reservation_cash == 'true' ? true : false , 
                                ] ,
                            ];
                            
                        @endphp
                        <div class="col-12">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th> @lang('dashboard.entry.name') </th>
                                    <th> @lang('dashboard.entry.status') </th>
                                    <th> @lang('messages.operations') </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0 ?>
                                @foreach($listAll as $order)
                                    <tr class="odd gradeX">
    
                                        <td>{{$order['name']}}</td>
                                       <td>
                                            @if($order['status'] == true)
                                                <span class="badge badge-success">{{ trans('dashboard.active') }}</span>
                                            @else 
                                            
                                            <span class="badge badge-secondary">{{ trans('dashboard.unactive') }}</span>
                                            @endif
                                        <td>
                                            <a class="btn btn-primary "
                                            href="{{$order['url']}}">
                                                <i class="fa fa-user-eye"></i> @lang('dashboard.settings')
                                            </a>

                                        </td>
                                    </tr>
    
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                           

                        </div>
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
@endsection

