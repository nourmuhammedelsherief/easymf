@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.branches')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
    <style>
        #barcode-svg{
            width: 1000px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.branches')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('branches.index')}}"></a>
                            @lang('messages.branches')
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')

    <section class="content">
        @if($branches->count() > 1)
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <a href="{{route('copyBranchMenu')}}" class="btn btn-success">
                        <i class="fa fa-plus"></i>
                        @lang('messages.copy_branch_menu')
                    </a>
                </div>
                <div class="col-sm-4"></div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <h3>
                    <a href="{{route('branches.create')}}" class="btn btn-info">
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
                                <th> @lang('messages.name') </th>
                                <th> @lang('messages.country') </th>
                                <th> @lang('messages.city') </th>
                                <th> @lang('messages.barcode') </th>
                                <th> @lang('messages.my_subscription') </th>
                                <th> @lang('messages.cart_show') </th>
                                <th> {{app()->getLocale() == 'ar' ? 'ايقاف المنيو': 'stop menu'}} </th>
                                <th> {{app()->getLocale() == 'ar' ? 'الفاتورة': 'Invoice'}} </th>
                                <th> {{app()->getLocale() == 'ar' ? 'فترات العمل': 'Periods'}} </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($branches as $branch)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($branch->name_ar == null ? $branch->name_en : $branch->name_ar) : ($branch->name_en == null ? $branch->name_ar : $branch->name_en)}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($branch->country->name_ar == null ? $branch->country->name_en : $branch->country->name_ar) : ($branch->country->name_en == null ? $branch->country->name_ar : $branch->country->name_en)}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($branch->city->name_ar == null ? $branch->city->name_en : $branch->city->name_ar) : ($branch->city->name_en == null ? $branch->city->name_ar : $branch->city->name_en)}}
                                    </td>
                                    <!--<td>-->
                                <!--    {{app()->getLocale() == 'ar' ? ($branch->subscription->package->name_ar == null ? $branch->subscription->package->name_en : $branch->subscription->package->name_ar) : ($branch->subscription->package->name_en == null ? $branch->subscription->package->name_ar : $branch->subscription->package->name_en)}}-->
                                    <!--</td>-->
                                    <td>
                                        <a href="{{route('branchBarcode' , $branch->id)}}" class="btn btn-success"><i
                                                class="fa fa-eye"></i>@lang('messages.show')</a>
                                    </td>
                                    <td>
                                        @if($branch->subscription->status == 'finished')
                                            <a href="{{route('get_branch_payment' , $branch->id)}}"
                                               class="btn btn-danger"> @lang('messages.finished') </a>
                                        @else
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#modal-{{$branch->id}}">
                                                @lang('messages.show')
                                            </button>
                                            <div class="modal fade" id="modal-{{$branch->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-default">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                @lang('messages.my_subscription')
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}}
                                                            </p>
                                                            <!--<p>-->
                                                        <!--    @lang('messages.subscribe_package')-->
                                                            <!--    <span style="color: blue">-->
                                                        <!--     {{ app()->getLocale() == 'ar' ? $branch->subscription->package->name_ar : $branch->subscription->package->name_en }}-->
                                                            <!--</span>-->
                                                            </p>
                                                            <p>
                                                                @lang('messages.subscribe_end_at')
                                                                <span style="color: #363cff">
                                                                    @if($branch->subscription->end_at != null)
                                                                        {{ $branch->subscription->end_at->format('Y-m-d') }}

                                                                    @endif
                                                            </span>
                                                            </p>
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
                                            @if($branch->subscription->end_at < \Carbon\Carbon::now()->addMonth() && $branch->subscription->status == 'active' && $branch->main == 'false')
                                                <a href="{{route('get_branch_payment' , $branch->id)}}"
                                                   class="btn btn-info"> تجديد </a>
                                            @endif
                                        @endif
                                        @if(($branch->subscription->status == 'tentative' && $branch->main == 'false' ) || ($branch->subscription->status == 'tentative_finished' && $branch->main == 'false'))
                                            <a href="{{route('get_branch_payment' , $branch->id)}}"
                                               class="btn btn-info"> @lang('messages.activate') </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($branch->cart == 'true')
                                            <a href="{{route('showBranchCart' , [$branch->id , 'false'])}}"
                                               class="btn btn-success"> @lang('messages.yes') </a>
                                        @else
                                            <a href="{{route('showBranchCart' , [$branch->id , 'true'])}}"
                                               class="btn btn-danger"> @lang('messages.no') </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($branch->stop_menu == 'true')
                                            <a href="{{route('stopBranchMenu' , [$branch->id , 'false'])}}"
                                               class="btn btn-success"> @lang('messages.yes') </a>
                                        @else
                                            <a href="{{route('stopBranchMenu' , [$branch->id , 'true'])}}"
                                               class="btn btn-danger"> @lang('messages.no') </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('print_invoice' , $branch->id)}}" target="_blank"  class="printPage btn btn-info">
                                            @lang('messages.show')
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{route('BranchPeriod' , $branch->id)}}" class="btn btn-secondary">
                                            {{\App\Models\RestaurantPeriod::whereBranchId($branch->id)->count()}}
                                        </a>
                                    </td>
                                    <td>

                                        <a class="btn btn-info" href="{{route('branches.edit' , $branch->id)}}">
                                            <i class="fa fa-user-edit"></i>
                                            {{app()->getLocale() == 'ar' ? 'بيانات الفرع' : 'branch data'}}
                                        </a>
                                        @php
                                            $user = Auth::guard('restaurant')->user();
                                            $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                            ->wherePermissionId(7)
                                            ->first();
                                        @endphp
                                        @if($branch->main == 'false' and ($user->type == 'restaurant' or $deletePermission))
                                            <a class="delete_data btn btn-danger" data="{{ $branch->id }}"
                                               data_name="{{ app()->getLocale() == 'ar' ? ($branch->name_ar == null ? $branch->name_en : $branch->name_ar) : ($branch->name_en == null ? $branch->name_ar : $branch->name_en) }}">
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
    {{$branches->links()}}
    <!-- /.row -->
    </section>
@endsection

@section('scripts')

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

                    window.location.href = "{{ url('/') }}" + "/restaurant/branches/delete/" + id;

                });

            });
        });
    </script>

@endsection

