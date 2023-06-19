@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.menu_categories')
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
                    <h1>@lang('messages.menu_categories')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('menu_categories.index')}}"></a>
                            @lang('messages.menu_categories')
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
                        <a class="btn btn-success" href="{{route('BranchMenuCategory' , $branch->id)}}">
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
                    <a href="{{route('menu_categories.create')}}" class="btn btn-info">
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
                                <th> @lang('messages.name') </th>
                                <th> @lang('messages.photo') </th>
                                @if(\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->menu_arrange == 'true')
                                    <th> @lang('messages.arrange') </th>
                                @endif
                                <th> {{app()->getLocale() == 'ar' ? 'رابط القسم':'Category Link'}} </th>
                                <th> @lang('messages.sub_categories') </th>
                                <th> @lang('messages.activity') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($categories as $category)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($category->branch->name_ar == null ? $category->branch->name_en : $category->branch->name_ar) : ($category->branch->name_en == null ? $category->branch->name_ar : $category->branch->name_en)}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($category->name_ar == null ? $category->name_en : $category->name_ar) : ($category->name_en == null ? $category->name_ar : $category->name_en)}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal-info-{{$category->id}}">
                                            <i class="fa fa-eye"></i>
                                            {{--                                            <img src="{{asset('/uploads/menu_categories/' . $category->photo)}}" width="100" height="100">--}}
                                        </button>
                                        <div class="modal fade" id="modal-info-{{$category->id}}">
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
                                                        @if($category->foodics_image != null)
                                                            <img src="{{$category->foodics_image}}" width="475"
                                                                 height="400">
                                                        @else
                                                            <img
                                                                src="{{asset('/uploads/menu_categories/' . $category->photo)}}"
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
                                    @if(\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->menu_arrange == 'true')
                                        <td>
                                            <a href="{{route('arrangeMenuCategory' , $category->id)}}"
                                               class="btn btn-success">
                                                @if($category->arrange == null)
                                                    لم يحدد
                                                @else
                                                    {{$category->arrange}}
                                                @endif
                                            </a>
                                        </td>
                                    @endif
                                    <td>
                                        @if($category->branch->main == 'true')
                                            <a class="btn btn-secondary" target="_blank"
                                               href="{{route('sliverHome' , [$category->restaurant->name_barcode , $category->id])}}">
                                                <i class="fa fa-eye"></i>
                                                @lang('messages.show')
                                            </a>
                                        @else
                                            <a class="btn btn-secondary" target="_blank"
                                               href="{{route('sliverHomeBranch' , [$category->restaurant->name_barcode , $category->branch->name_barcode , $category->id])}}">
                                                <i class="fa fa-eye"></i>
                                                @lang('messages.show')
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($category->branch->foodics_status == 'false')
                                            <a class="btn btn-primary"
                                               href="{{route('sub_categories.index' , $category->id)}}">
                                                {{$category->sub_categories->count()}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="custom-switch {{$category->active == 'true' ? 'on' : 'off'}}"
                                              data-url_on="{{route('activeMenuCategory' , [$category->id , 'false'])}}"
                                              data-url_off="{{route('activeMenuCategory' , [$category->id , 'true'])}}">
                                            <span class="text">On</span>
                                            <span class="move"></span>
                                        </span>

                                    </td>
                                    <td>
                                        <a class="btn btn-secondary"
                                           href="{{route('copyMenuCategory' , $category->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.copy')
                                        </a>
                                        <a class="btn btn-info"
                                           href="{{route('menu_categories.edit' , $category->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        @if($category->branch->foodics_status == 'false')
                                            @php
                                                $user = Auth::guard('restaurant')->user();
                                                $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                                ->wherePermissionId(7)
                                                ->first();
                                            @endphp
                                            @if($user->type == 'restaurant' or $deletePermission)
                                                <a class="delete_data btn btn-danger" data="{{ $category->id }}"
                                                   data_name="{{ app()->getLocale() == 'ar' ? ($category->name_ar == null ? $category->name_en : $category->name_ar) : ($category->name_en == null ? $category->name_ar : $category->name_en) }}">
                                                    <i class="fa fa-key"></i> @lang('messages.delete')
                                                </a>
                                            @endif
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
    {{$categories->links()}}

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

                    window.location.href = "{{ url('/') }}" + "/restaurant/menu_categories/delete/" + id;

                });

            });
        });
    </script>
@endsection


