@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.products')
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
                    <h1>@lang('messages.products')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('products.index')}}"></a>
                            @lang('messages.products')
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                @if($branches->count() > 0)
                    @foreach($branches as $branch)
                        <span>
                        <a class="btn btn-success" href="{{route('BranchProducts' , $branch->id)}}">
                            {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}}
                        </a>
                    </span>
                    @endforeach
                @endif

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <h3>
                    <a href="{{route('products.create')}}" class="btn btn-info">
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
                                <th> @lang('messages.category') </th>
                                <th> @lang('messages.name') </th>
                                <th> @lang('messages.photo') </th>
                                <th> @lang('messages.price') </th>
                                <th> @lang('messages.options') </th>
                                <th> @lang('messages.sizes') </th>
                                @if(\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->product_arrange == 'true')
                                    <th> @lang('messages.arrange') </th>
                                @endif
                                <th> @lang('messages.activity') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($products as $product)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($product->branch->name_ar == null ? $product->branch->name_en : $product->branch->name_ar) : ($product->branch->name_en == null ? $product->branch->name_ar : $product->branch->name_en)}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($product->menu_category->name_ar == null ? $product->menu_category->name_en : $product->menu_category->name_ar) : ($product->menu_category->name_en == null ? $product->menu_category->name_ar : $product->menu_category->name_en)}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($product->name_ar == null ? $product->name_en : $product->name_ar) : ($product->name_en == null ? $product->name_ar : $product->name_en)}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal-info-{{$product->id}}">
                                            <i class="fa fa-eye"></i>
                                            {{--                                            <img src="{{asset('/uploads/menu_categories/' . $product->photo)}}" width="100" height="100">--}}
                                        </button>
                                        <div class="modal fade" id="modal-info-{{$product->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-info">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            @lang('messages.photo')
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($product->foodics_image != null)
                                                            <img src="{{$product->foodics_image}}" width="475"
                                                                 height="400">
                                                        @else
                                                            <img src="{{asset('/uploads/products/' . $product->photo)}}"
                                                                 width="475" height="400">
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-outline-light"
                                                                data-dismiss="modal">
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
                                        {{$product->price}}
                                    </td>
                                    <td>
                                        <a href="{{route('productOption' , $product->id)}}"
                                           class="btn btn-primary"> {{$product->options->count()}} </a>
                                    </td>
                                    <td>
                                        <a href="{{route('productSize' , $product->id)}}"
                                           class="btn btn-info"> {{$product->sizes->count()}} </a>
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->product_arrange == 'true')
                                        <td>
                                            <a href="{{route('arrangeProduct' , $product->id)}}"
                                               class="btn btn-success">
                                                @if($product->arrange == null)
                                                    لم يحدد
                                                @else
                                                    {{$product->arrange}}
                                                @endif
                                            </a>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="custom-switch {{$product->active == 'true' ? 'on' : 'off'}}"
                                              data-url_on="{{route('activeProduct' , [$product->id , 'false'])}}"
                                              data-url_off="{{route('activeProduct' , [$product->id , 'true'])}}">
                                            <span class="text">On</span>
                                            <span class="move"></span>
                                        </span>


                                    </td>
                                    <td>
                                        <a class="btn btn-info" href="{{route('products.edit' , $product->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        <a class="btn btn-secondary" href="{{route('copyProduct' , $product->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.copy')
                                        </a>
                                        @php
                                            $user = Auth::guard('restaurant')->user();
                                            $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                            ->wherePermissionId(7)
                                            ->first();
                                        @endphp
                                        @if($user->type == 'restaurant' or $deletePermission)
                                            <a class="delete_data btn btn-danger" data="{{ $product->id }}"
                                               data_name="{{ app()->getLocale() == 'ar' ? ($product->name_ar == null ? $product->name_en : $product->name_ar) : ($product->name_en == null ? $product->name_ar : $product->name_en) }}">
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
    {{$products->links()}}

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

                    window.location.href = "{{ url('/') }}" + "/restaurant/products/delete/" + id;

                });

            });
        });
    </script>
@endsection

