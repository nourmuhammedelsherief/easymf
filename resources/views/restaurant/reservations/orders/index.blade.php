@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.reservation_orders')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
    <style>
        .modal-body .periods .row > div > div,
        .modal-body .dates .date > div {
            border: 1px solid #CCC;
            border-radius: 10px;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .modal-body .periods .row > div > div.active,
        .modal-body .dates .date > div.active {
            background-color: rgba(255, 0, 0, 0.3)
        }

        .text-bold {
            font-weight: bold !important;
        }

        #userInfo .row > div {
            margin-top: 10px;
        }
        td > .btn{
            margin-bottom: 10px;
        }
        @media(max-width:400px){
            .modal-dialog{
                width: 285px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.reservation_orders')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('restaurant/reservation/orders')}}"></a>
                            @lang('dashboard.reservation_orders')
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
                    
                        <a href="{{route('resetaurant.reservation.finished')}}" class="btn btn-danger">
                            الحجوزات المنتهية
                        </a>
                        <a href="{{route('resetaurant.reservation.canceled')}}" class="btn btn-danger">
                            الحجوزات الملغية
                        </a>
                        <a href="{{route('resetaurant.reservation.confirmed')}}" class="btn btn-primary">
                            الحجوزات المؤكدة
                        </a>
                        
                    
                        <a href="{{route('restaurant.reservation.index')}}" class="btn btn-info">
                            الحجوزات النشطة
                        </a>
                </h3>
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th> @lang('dashboard.entry.type') </th>
                                <th> @lang('messages.branch') </th>
                                <th> @lang('dashboard.places') </th>
                                <th> @lang('messages.price') </th>
                                <th> @lang('messages.date') </th>
                                <th> @lang('dashboard.time_from') </th>
                                <th> @lang('dashboard.time_to') </th>
                                <th> @lang('dashboard.people_count') </th>
                                <th> @lang('dashboard.details') </th>
                                <th> @lang('dashboard.entry.payment_status') </th>
                                @if(isset($status) and $status == 'cenceled')
                                <th>{{ trans('dashboard.entry.reason') }}</th>
                                @endif
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($orders as $order)
                                <tr class="odd gradeX">

                                    <td>{{$order->id}}</td>
                                    <td>
                                        @if($order->type == 'table')
                                        <span class="badge badge-primary">{{ trans('dashboard.table') }}</span>
                                        @elseif($order->type == 'chair')
                                        <span class="badge badge-success">{{ trans('dashboard.chair') }}</span>
                                        @elseif($order->type == 'package')
                                        <span class="badge badge-danger">{{ trans('dashboard.package') }}</span>
                                        
                                        @endif
                                    </td>
                                    <td>
                                        {{isset($order->table->branch->id) ? $order->table->branch->name : ''}}
                                    </td>
                                    <td>
                                        {{isset($order->table->place->id) ? $order->table->place->name : ''}}
                                    </td>
                                    <td> {{$order->total_price}} </td>
                                    <td>{{$order->date}}</td>
                                    <td>{{$order->period->from_string}}</td>
                                    <td>{{$order->period->to_string}}</td>
                                    <td> {{$order->table->people_count}} </td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#userInfo-{{$order->id}}">
                                            {{ trans('dashboard.details') }}
                                        </button>
                                        <div class="modal fade" id="userInfo-{{$order->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="userInfoTitle"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="userInfoTitle">{{ trans('dashboard.details') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        
                                                    </div>
                                                    <div class="modal-body">
                                                        @include('restaurant.reservations.orders.user_info')
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-info print-user-info" data-id="{{$order->id}}">
                                                            <i class="fas fa-print"></i>
                                                            {{ trans('dashboard.print') }}</button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ trans('dashboard.close') }}</button>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $order->getPaymentStatusHtml() !!}
                                        @if(!empty($order->invoice_id))
                                            <p class="text-center"> {{ trans('dashboard.pay_number') }} : {{$order->invoice_id}}</p>
                                        @endif
                                    </td>
                                    @if(isset($status) and $status == 'cenceled')
                                    <td>
                                        {!! $order->reason !!}
                                    </td>
                                    
                                    @endif
                                
                                    <td>
                                        @if($order->payment_type == 'bank' and !empty($order->transfer_photo))
                                                <a  class="btn btn-info show-image"
                                                    data-image="{{asset($order->image_path)}}"
                                                    data-toggle="modal"
                                                    data-target="#showImage-{{$order->id}}">
                                                    <i class="fa fa-user-eye"></i> @lang('dashboard.transfer_photo')
                                                </a>
                                                
                                                <div class="modal fade" id="showImage-{{$order->id}}" tabindex="-1" role="dialog"
                                                        aria-labelledby="showImageTitle"
                                                        aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="showImageTitle">{{ trans('dashboard.transfer_photo') }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="image-preview">
                                                                    <img src="{{asset($order->image_path)}}" width="475" height="400">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">{{ trans('dashboard.close') }}</button>
                                                                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif
                                       @if(!isset($status) or $status != 'finished')
                                            @if($order->is_confirm === 0 and $order->status != 'canceled') 
                                                <a class="btn btn-success confirm-order" data-toggle="modal" data-target="#orderConfirm" data-id="{{$order->id}}" style="color:#FFF;">
                                                    <i class="fa fa-user-eye"></i> @lang('dashboard.reservation_confirm')
                                                </a>
                                                
                                            @endif
                                            @if(in_array($order->status , ['not_paid' , 'paid']) and $order->is_confirm === 0)
                                                
                                                <a class="btn btn-danger cancel-order"
                                                href="javascript:;" data-toggle="modal" data-target="#cancelOrder" data-href="{{route('resetaurant.reservation.confirm' , $order->id)}}">
                                                @lang('dashboard.cancel')
                                            </a>
                                            @endif
                                            
                                            @if($order->payment_type == 'bank' and !empty($order->transfer_photo) and $order->status == 'not_paid')
                                                <a class="btn btn-primary "
                                                    href="{{route('resetaurant.reservation.confirm' , $order->id)}}">
                                                    <i class="fa fa-user-eye"></i> @lang('dashboard.payment_confirm')
                                                </a>

                                                {{-- <a class="btn btn-danger "
                                                    href="{{route('resetaurant.reservation.confirm' , $order->id)}}?cancel=1">
                                                    @lang('dashboard.cancel')
                                                </a> --}}
                                            @endif

                                       @endif


                                        {{-- <a class="delete_data btn btn-danger" data="{{ $order->id }}" data_name="{{ app()->getLocale() == 'ar' ? ($order->name_ar == null ? $order->name_en : $order->name_ar) : ($order->name_en == null ? $order->name_ar : $order->name_en) }}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a> --}}

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
    <div class="modal fade" id="orderConfirm" tabindex="-1" role="dialog"
        aria-labelledby="orderConfirmTitle"
        aria-hidden="true">
       <div class="modal-dialog " role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title"
                       id="orderConfirmTitle">{{ trans('dashboard.reservation_confirm') }}</h5>
                   <button type="button" class="close" data-dismiss="modal"
                           aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{ trans('dashboard.reservation_number') }}</label>
                        <input type="text" name="code" class="form-control"  data-id="">
                    </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary"
                           data-dismiss="modal">{{ trans('dashboard.close') }}</button>
                   <button type="button" class="btn btn-primary " id="checkReservationConfirm">{{ trans('dashboard.confirm') }}</button>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade" id="cancelOrder" tabindex="-1" role="dialog"
   aria-labelledby="cancelOrderTitle"
   aria-hidden="true">
  <div class="modal-dialog " role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title"
                  id="cancelOrderTitle">{{ trans('dashboard.reservation_cancel_order') }}</h5>
              <button type="button" class="close" data-dismiss="modal"
                      aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
               <form action="" method="post">
                    @csrf
                    <input type="hidden" name="cancel" value="1" >
                    <div class="form-group">
                        <label for="">{{ trans('dashboard.entry.reason') }}</label>
                        <textarea name="reason" id=""  rows="5" class="form-control">{{old('reason')}}</textarea>
                    </div>
               </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary"
                      data-dismiss="modal">{{ trans('dashboard.close') }}</button>
              <button type="button" class="btn btn-primary "    data-dismiss="modal"  id="checkReservationCancel">{{ trans('dashboard.save') }}</button>
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
    <script src="{{asset('dist/js/html2canvas.min.js')}}"></script>
    <script>
        $(function () {

            $('table').on("click" , '.print-user-info', function() {
                console.log('print');
                var tag = $(this);
                html2canvas(tag.parent().parent().find('.modal-body')[0]).then(function (canvas) {			var anchorTag = document.createElement("a");
                    // $('#share-file').attr('src' , canvas.toDataURL());
                    document.body.appendChild(anchorTag);
                    // document.getElementById("previewImg").appendChild(canvas);
                    anchorTag.download = "reservation-order-"+tag.data('id')+".jpg";
                    anchorTag.href = canvas.toDataURL();
                    anchorTag.target = '_blank';
                    anchorTag.click();
                    
                        
                });
            });
            $('table').on('click' , 'a.confirm-order' , function(){
                $('#orderConfirm input').data('id' , $(this).data('id'));
            });


            $('#checkReservationConfirm').on('click' , function(){
                var input = $('#orderConfirm input');
                $.ajax({
                    url : "{{url('restaurant/reservation/orders/confirm')}}/" + input.data('id') + '/' + input.val() , 
                    method : 'GET' , 
                    success: function(json){
                        if(json.status == true){
                            toastr.success(json.message);
                            $('#orderConfirm').modal('hide');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }else{
                            toastr.error(json.message);
                        }
                    }
                });
            });
            $('table').on('click' , '.btn.cancel-order' , function(){
                var tag  = $(this);
                $('#cancelOrder form').attr('action' , tag.data('href'));
            });
            $('#checkReservationCancel').on('click' , function(){
                var input = $('#orderConfirm input');
                $('#cancelOrder form').submit();
                
            });
            $("#example1").DataTable({
                lengthMenu: [
                    [10, 25, 50 , 100, -1],
                    [10, 25, 50,  100,'All'],
                ],
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
            });
            $('.show-details').on('click', function (json) {
                $('#userInfo .modal-body').html('');
                $.ajax({
                    url: "{{route('restaurant.reservation.index')}}/" + $(this).data('id'),
                    method: 'GET',
                    success: function (json) {
                        console.log(json);
                        $('#userInfo .modal-body').html(json.order);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('body').on('click', '.show-image', function () {

                $('#showImage .image-preview img').prop('src', $(this).data('image'));
                $('#showImage').modal('show');
            });
        });
    </script>
@endsection

