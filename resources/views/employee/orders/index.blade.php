@extends('employee.lteLayout.master')

@section('title')
    @lang('messages.orders')
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
                    <h1>@lang('messages.orders')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            @if($type == 'delivery')
                                <a href="{{route('employeeDeliveryOrders' , $status)}}"></a>
                            @elseif($type == 'takeaway')
                                <a href="{{route('employeeTakeawayOrders' , $status)}}"></a>
                            @endif
                            @lang('messages.orders')
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
                    <div class="col-sm-3">
                        @if($type == 'delivery')
                            <a href="{{route('employeeDeliveryOrders' , $status)}}" class="btn btn-secondary">
                                <i class="fa fa-refresh"></i>
                            </a>
                        @elseif($type == 'takeaway')
                            <a href="{{route('employeeTakeawayOrders' , $status)}}" class="btn btn-secondary">
                                <i class="fa fa-refresh"></i>
                            </a>
                        @endif
                    </div>
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
                                <th> @lang('messages.order_num') </th>
                                <th> @lang('messages.client') </th>
                                <th> @lang('messages.order_type') </th>
                                <th> @lang('messages.payment_type') </th>
                                <th> @lang('messages.order_review') </th>
                                <th> @lang('messages.order_status') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($orders as $order)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        # {{$order->id}}
                                    </td>
                                    <td>
                                        @if($order->user != null)
                                            {{$order->user->name}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->type == 'delivery')
                                            {{app()->getLocale() == 'ar' ? 'ديلفري' : 'Delivery' }}
                                        @elseif($order->type == 'takeaway')
                                            {{app()->getLocale() == 'ar' ? 'أستلام  من الفرع' : 'Takeaway' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->payment_method == 'receipt_payment')
                                            {{app()->getLocale() == 'ar' ? 'الدفع عند الأستلام' : 'Cash' }}
                                        @elseif($order->payment_method == 'online_payment')
                                            {{app()->getLocale() == 'ar' ? 'دفع أونلاين' : 'Online Payment' }}
                                        @elseif($order->payment_method == 'loyalty_point')
                                          {{ trans('messages.' . $order->payment_method) }}
                                        @endif
                                    </td>
                                    {{--                                    <td>--}}
                                    {{--                                        @if($order->status == 'new')--}}
                                    {{--                                            {{app()->getLocale() == 'ar' ? 'جديد' : 'New' }}--}}
                                    {{--                                        @elseif($order->status == 'active')--}}
                                    {{--                                            {{app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}--}}
                                    {{--                                        @elseif($order->status == 'completed')--}}
                                    {{--                                            {{app()->getLocale() == 'ar' ? 'مكتمل' : 'Completed' }}--}}
                                    {{--                                        @elseif($order->status == 'canceled')--}}
                                    {{--                                            {{app()->getLocale() == 'ar' ? 'ملغي' : 'Canceled' }}--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </td>--}}

                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-{{$order->id}}">
                                            @lang('messages.show')
                                        </button>
                                        <div class="modal fade" id="modal-{{$order->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-default">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            @lang('messages.order_review')
                                                            (@lang('messages.products'))
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($order->order_items->count() > 0)
                                                            @foreach($order->order_items as $item)
                                                                <p>
                                                                    @lang('messages.name')
                                                                    <span style="color: red">
                                                                        {{ app()->getLocale() == 'ar' ? $item->product->name_ar : $item->product->name_en}}
                                                                    </span>
                                                                </p>
                                                                <p>
                                                                    @lang('messages.product_count')
                                                                    <span style="color: red">
                                                                        {{ $item->product_count}}
                                                                    </span>
                                                                </p>
                                                                <p>
                                                                    @lang('messages.product_price')
                                                                    <span style="color: red">
                                                                        {{ $item->price}}
                                                                        {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    </span>
                                                                </p>
                                                                @if($item->order_item_options->count() > 0)
                                                                    <h5 class="text-center">
                                                                        @lang('messages.options')
                                                                    </h5>
                                                                    @foreach($item->order_item_options as $option)
                                                                        <p>
                                                                            @lang('messages.option')
                                                                            <span style="color: red">
                                                                                {{ app()->getLocale() == 'ar' ? $option->option->name_ar : $option->option->name_en}}
                                                                            </span>
                                                                        </p>
                                                                        <p>
                                                                            @lang('messages.option_count')
                                                                            <span style="color: red">
                                                                                {{ $option->option_count}}
                                                                            </span>
                                                                        </p>
                                                                        <p>
                                                                            @lang('messages.option_price')
                                                                            <span style="color: red">
                                                                                {{ $option->option->price}}
                                                                                {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                            </span>
                                                                        </p>
                                                                    @endforeach
                                                                @endif
                                                                <br>
                                                                <hr>
                                                            @endforeach
                                                            <p>
                                                                @lang('messages.order_value')
                                                                <span style="color: red">
                                                                        {{$order->order_price}}
                                                                    {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    </span>
                                                            </p>
                                                            @if($order->tax != null)
                                                                <p>
                                                                    @lang('messages.tax')
                                                                    <span style="color: red">
                                                                        {{$order->tax}}
                                                                        {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    </span>
                                                                </p>
                                                            @endif
                                                            @if($order->discount_value != null)
                                                                <p>
                                                                    @lang('messages.discount')
                                                                    <span style="color: red">
                                                                        {{$order->discount_value}}
                                                                        {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    </span>
                                                                </p>
                                                            @endif
                                                            @if($order->delivery_value != null)
                                                                <p>
                                                                    @lang('messages.delivery_value')
                                                                    <span style="color: red">
                                                                        {{$order->delivery_value}}
                                                                        {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    </span>
                                                                </p>
                                                            @endif
                                                            <p>
                                                                @lang('messages.total')
                                                                <span style="color: red">
                                                                        @if($order->delivery_value != null)
                                                                        {{$order->total_price + $order->delivery_value}}
                                                                        {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    @else
                                                                        {{$order->total_price}}
                                                                        {{app()->getLocale() == 'ar' ? $order->restaurant->country->currency_ar : $order->restaurant->country->currency_en}}
                                                                    @endif
                                                                    </span>
                                                            </p>
                                                            @if($order->notes)
                                                                <p>
                                                                    @lang('messages.notes') : <br>
                                                                    <span> {{$order->notes}} </span>
                                                                </p>
                                                            @endif


                                                        @else
                                                            <p>
                                                                @lang('messages.on_orders')
                                                            </p>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-outline-light"
                                                                data-dismiss="modal">@lang('messages.close')</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </td>
                                    <td>
                                        @if(!in_array($order->status , ['completed' , 'canceled']))
                                        <form method="post" action="{{route('change_order_status' , $order->id)}}">
                                            @csrf
                                            <select name="status" class="form-control" required>
                                                {{--                                                <option value="new"> @lang('messages.new') </option>--}}
                                                <option
                                                    value="active" {{$order->status == 'active' ? 'selected' : ''}}> @lang('messages.active') </option>
                                                <option
                                                    value="completed" {{$order->status == 'completed' ? 'selected' : ''}}> @lang('messages.completed') </option>
                                                <option
                                                    value="canceled" {{$order->status == 'canceled' ? 'selected' : ''}}> @lang('messages.canceled') </option>
                                            </select>
                                            <button type="submit"
                                                    class="btn btn-info"> @lang('messages.change') </button>
                                        </form>
                                        @else 
                                        ----
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
    {{$orders->links()}}

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
@endsection
