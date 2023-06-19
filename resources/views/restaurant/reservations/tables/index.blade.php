@extends('restaurant.lteLayout.master')

@section('title')
    @lang($type == 'table' ? 'dashboard.reservation_tables' : 'dashboard.reservation_chairs')
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
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang($type == 'table' ? 'dashboard.reservation_tables' : 'dashboard.reservation_chairs')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('restaurant/reservation/tables')}}"></a>
                            @lang($type == 'table' ? 'dashboard.reservation_tables' : 'dashboard.reservation_chairs')
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
                    <a href="{{url('/restaurant/reservation/tables/create?type=table')}}" class="btn btn-info">
                        <i class="fa fa-plus"></i>
                        @lang('dashboard.add_table')
                    </a>
                    <a href="{{url('/restaurant/reservation/tables/create?type=chair')}}" class="btn btn-info">
                        <i class="fa fa-plus"></i>
                        @lang('dashboard.add_chair')
                    </a>
                    <a href="{{url('/restaurant/reservation/tables/create?type=package')}}" class="btn btn-info">
                        <i class="fa fa-plus"></i>
                        @lang('dashboard.add_package')
                    </a>
                    @if($action == 'index')
                        <a href="{{url('/restaurant/reservation/tables-expire?type=table')}}" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                            @lang('messages.reservation_expired')
                        </a>
                        <a href="{{url('/restaurant/reservation/tables-expire?type=chair')}}" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                            @lang('messages.reservation_chair_expired')
                        </a>
                        <a href="{{url('/restaurant/reservation/tables-expire?type=package')}}" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                            @lang('messages.reservation_package_expired')
                        </a>
                    @else
                        <a href="{{url('/restaurant/reservation/tables')}}" class="btn btn-success">
                            <i class="fa fa-list"></i>
                            @lang('messages.reservation_active')
                        </a>
                    @endif
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
                                <th> @lang('dashboard.entry.type') </th>
                                <th> @lang('messages.branch') </th>
                                <th> @lang('dashboard.places') </th>
                                <th> @lang('messages.price') </th>
                                <th> @lang('dashboard.people_count') </th>
                                <th> @lang('dashboard.table_count') </th>
                                <th> @lang('dashboard.entry.status') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($tables as $table)

                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        @if($table->type == 'table')
                                            <span class="badge badge-primary">{{ trans('dashboard.table') }}</span>
                                        @elseif($table->type == 'chair')
                                            <span class="badge badge-success">{{ trans('dashboard.chair') }}</span>
                                        @elseif($table->type == 'package')
                                            <span class="badge badge-danger">{{ trans('dashboard.package') }}</span>

                                        @endif
                                    </td>
                                    <td>
                                        {{$table->branch->name}}
                                    </td>
                                    <td>
                                        {{$table->place->name}}
                                    </td>
                                    <td> {{$table->price}} </td>
                                    <td> {{$table->people_count}} </td>
                                    <td> {{$table->table_count}} </td>
                                    <td>
                                            <span class="custom-switch {{$table->status == 'available' ? 'on' : 'off'}}"
                                                  data-url_on="{{route('restaurant.reservation.tables.changeStatus' , $table->id) . '?status=0'}}"
                                                  data-url_off="{{route('restaurant.reservation.tables.changeStatus' , $table->id) . '?status=1'}}">
                                                <span class="text">On</span>
                                                <span class="move"></span>
                                            </span>


                                    </td>
                                    <td>

                                        <a class="btn btn-info show-data" data-id="{{$table->id}}" href="javascript:;"
                                           data-toggle="modal" data-target="#showTableData">
                                            <i class="fa fa-user-eye"></i> @lang('dashboard.view')
                                        </a>
                                        <a class="btn btn-primary " data-id="{{$table->id}}"
                                           href="{{url('/restaurant/reservation/tables/' . $table->id . '/edit')}}">
                                            <i class="fas fa-edit"></i> @lang('messages.edit')
                                        </a>
                                        @php
                                            $user = Auth::guard('restaurant')->user();
                                            $deletePermission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)
                                            ->wherePermissionId(7)
                                            ->first();
                                        @endphp
                                        @if($user->type == 'restaurant' or $deletePermission)
                                            <a class="delete_data btn btn-danger" data="{{ $table->id }}"
                                               data_name="{{ app()->getLocale() == 'ar' ? ($table->name_ar == null ? $table->name_en : $table->name_ar) : ($table->name_en == null ? $table->name_ar : $table->name_en) }}">
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
    {{$tables->links()}}
    <!-- /.row -->
    </section>

    @if($tables->count() > 0)
        <!-- Modal -->
        <div class="modal fade" id="showTableData" tabindex="-1" role="dialog" aria-labelledby="showTableDataTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showTableDataTitle">{{ trans('dashboard.reservation_table') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('restaurant.reservations.tables.show')
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
            $('table').on('click', 'a.show-data', function () {
                console.log('test');
                var tag = $(this);
                $.ajax({
                    url: "{{url('restaurant/reservation/tables')}}/" + tag.data('id'),
                    method: "GET",
                    success: function (json) {
                        console.log(json);
                        $('#showTableData .modal-body').html(json.html);
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

                    window.location.href = "{{ url('/') }}" + "/restaurant/reservation/tables/delete/" + id;

                });

            });
        });
    </script>
@endsection

