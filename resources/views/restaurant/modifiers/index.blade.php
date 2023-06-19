@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.modifiers')
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
                    <h1>@lang('messages.modifiers')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('modifiers.index')}}"></a>
                            @lang('messages.modifiers')
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
                    <a href="{{route('modifiers.create')}}" class="btn btn-info">
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
                                <th> @lang('messages.activity') </th>
                                <th> {{app()->getLocale() == 'ar' ? 'الاختيار': 'choose'}}</th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($modifiers as $modifier)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($modifier->name_ar == null ? $modifier->name_en : $modifier->name_ar) : ($modifier->name_en == null ? $modifier->name_ar : $modifier->name_en)}}
                                    </td>
                                    <td>
                                        <span class="custom-switch {{$modifier->is_ready == 'true' ? 'on' : 'off'}}"
                                              data-url_on="{{route('activeModifier' , [$modifier->id , 'false'])}}"
                                              data-url_off="{{route('activeModifier' , [$modifier->id , 'true'])}}">
                                            <span class="text">On</span>
                                            <span class="move"></span>
                                        </span>


                                    </td>
                                    <td>
                                        @if($modifier->choose == 'one')
                                            {{app()->getLocale() == 'ar' ? 'واحد': 'One'}}
                                        @else
                                            {{app()->getLocale() == 'ar' ? 'متعدد': 'Multiple'}}
                                        @endif
                                    </td>
                                    <td>

                                        <a class="btn btn-info" href="{{route('modifiers.edit' , $modifier->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        @php
                                            $user = Auth::guard('restaurant')->user();
                                            $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                            ->wherePermissionId(7)
                                            ->first();
                                        @endphp
                                        @if($user->type == 'restaurant' or $deletePermission)
                                            <a class="delete_data btn btn-danger" data="{{ $modifier->id }}"
                                               data_name="{{ app()->getLocale() == 'ar' ? ($modifier->name_ar == null ? $modifier->name_en : $modifier->name_ar) : ($modifier->name_en == null ? $modifier->name_ar : $modifier->name_en) }}">
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
    {{$modifiers->links()}}

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

                    window.location.href = "{{ url('/') }}" + "/restaurant/modifiers/delete/" + id;

                });

            });
        });
    </script>
@endsection

