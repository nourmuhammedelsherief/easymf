@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.foodics_orders')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    
    <!-- Theme style -->
    <style>
        #foodicsInfo .title{
            font-weight: bold;
            margin-bottom: 10px;
        }
        #foodicsInfo .content{
            color:rgb(57, 57, 57);
            font-size: 14px;
        }
        tr td:last-child .btn{
            margin: 5px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                      
                            @lang('dashboard.foodics_orders')
                        

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
                            <a href="{{route('tables.index')}}"></a>
                            @lang('dashboard.foodics_orders')
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
                                
                                {{-- <th></th> --}}
                                <th> @lang('dashboard.entry.order_num') </th>
                                <th> @lang('dashboard.table') </th>
                                <th> @lang('dashboard.branch') </th>
                                <th> @lang('dashboard.status') </th>
                                <th> @lang('dashboard.entry.payment_method') </th>
                                <th> @lang('dashboard.entry.payment_status') </th>
                                <th> @lang('dashboard.entry.price') </th>
                                <th> @lang('dashboard.entry.created_at') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($orders as $order)
                                <tr class="odd gradeX">
                                   
                                    {{-- <td><?php echo ++$i ?></td> --}}
                                    <td>{{$order->id}}</td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($order->table->name_ar == null ? $order->table->name_en : $order->table->name_ar) : ($order->table->name_en == null ? $order->table->name_ar : $order->table->name_en)}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($order->branch->name_ar == null ? $order->branch->name_en : $order->branch->name_ar) : ($order->branch->name_en == null ? $order->branch->name_ar : $order->branch->name_en)}}
                                    </td>
                                    <td>
                                        {!! $order->getStatusHtml() !!}
                                    </td>
                                    <td>
                                        @if($order->payment_type == 'online') 
                                        <span class="badge badge-success">{{ trans('dashboard.online_payment') }}</span>
                                        @elseif($order->payment_type == 'cash')
                                        <span class="badge badge-primary">{{ trans('dashboard.cash') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($order->payment_type == 'online') 
                                            @if($order->payment_status == 'true')
                                                <span class="badge badge-success">{{ trans('dashboard.paid') }}</span>
                                            @else 
                                                <span class="badge badge-danger">{{ trans('dashboard.unpaid') }}</span>
                                            @endif
                                        
                                        @endif
                                    </td>
                                    
                                    <td>
                                        {{number_format($order->total_price)}}
                                    </td>
                                    <td>{{date('Y-m-d h:i A' ,strtotime($order->created_at))}}</td>
                                    <td>
                                        <button class="btn btn-primary foodicsInfo foodics-info-{{$order->id}}" data-id="{{$order->id}}" data-toggle="modal" data-target="#foodicsInfo">{{ trans('dashboard.foodics_status') }}</button>

                                        <button class="btn btn-info order-details" data-id="{{$order->id}}" data-toggle="modal" data-target="#orderDetails">{{ trans('dashboard.order_details') }}</button>
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
    {{$orders->links()}}

    <!-- /.row -->
    </section>

    <!-- Modal -->
<div class="modal fade" id="foodicsInfo" tabindex="-1" role="dialog" aria-labelledby="foodicsInfoLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="foodicsInfoLabel">{{ trans('dashboard.foodics_status') }}</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
		  ...
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('dashboard.close') }}</button>
		</div>
	  </div>
	</div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="orderDetails" tabindex="-1" role="dialog" aria-labelledby="orderDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="orderDetailsLabel">{{ trans('dashboard.foodics_status') }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('dashboard.close') }}</button>
            </div>
          </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        function foodicsInfo(id){
            var modal = $('#foodicsInfo');
            console.log('test');
            modal.find('.modal-body').html('<div class="text-center mt-5 mb-5"><span class="loader"></span></div>');
            $.ajax({
                url : "{{route('FoodicsTableInfo')}}" , 
                method : 'GET' , 
                data : {
                    order_id : id , 
                },
                success: function(json){
                    console.log(json);
                    if(json.status == true){
                        modal.find('.modal-body').html(json.data.content);
                    }
                } , 
                error: function(xhr){
                    console.log(xhr);
                    modal.find('.modal-body').html('');
                    toastr.error('fail');
                }, 
            });
        }
        $(function () {
            $('#foodicsInfo').on('click' , '.create-foodics-order' , function(){
                var tag = $(this);
                var modal = $('#foodicsInfo');
                console.log(tag.data());
                modal.find('.modal-body').html('<div class="text-center mt-5 mb-5"><span class="loader"></span></div>');
                $.ajax({
                    url : "{{route('CreateFoodicsOrder')}}" , 
                    method : 'GET' , 
                    data : {
                        order_id : tag.data('id') , 
                    },
                    success: function(json){
                        console.log(json);
                        if(json.status == true){
                            toastr.success(json.message);
                            modal.find('.modal-body').html(json.content);
                        }else{
                            toastr.error(json.message);
                            modal.find('.modal-body').html(json.content);
                        }
                        
                    } , 
                    error: function(xhr){
                        console.log(xhr);
                        modal.find('.modal-body').html('');
                        toastr.error('fail');
                    }, 
                });
            });
            $('table').on('click' , '.btn.foodicsInfo' , function(){
                var tag = $(this);
                foodicsInfo(tag.data('id'));
            });
            $('table').on('click' , '.btn.order-details' , function(){
                var tag = $(this);
                var modal = $('#orderDetails');
                console.log(tag.data());
                modal.find('.modal-body').html('<div class="text-center mt-5 mb-5"><span class="loader"></span></div>');
                $.ajax({
                    url : "{{route('orderDetails')}}" , 
                    method : 'GET' , 
                    data : {
                        order_id : tag.data('id') , 
                    },
                    success: function(json){
                        console.log(json);
                        if(json.status == true){
                            modal.find('.modal-body').html(json.data.content);
                        }
                    } , 
                    error: function(xhr){
                        console.log(xhr);
                        modal.find('.modal-body').html('');
                        toastr.error('fail');
                    }, 
                });
            });
            
            $("#example1").DataTable({
                order : [[0, 'desc']] , 
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

                    window.location.href = "{{ url('/') }}" + "/restaurant/tables/delete/" + id;

                });

            });
        });
    </script>
@endsection
