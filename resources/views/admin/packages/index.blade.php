@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.packages')
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
                    <h1>@lang('messages.packages')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('packages.index')}}"></a>
                            @lang('messages.packages')
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
                                        <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th>@lang('messages.name')</th>
                                <th>@lang('messages.price')</th>
                                <th>@lang('messages.branch_price')</th>
                                <th>@lang('messages.duration')</th>
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($packages as $package)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        @if(app()->getLocale() == 'ar')
                                            {{$package->name_ar}}
                                        @else
                                            {{$package->name_en}}
                                        @endif
                                    </td>
                                    <td> {{$package->price}} </td>
                                    <td> {{$package->branch_price}} </td>
                                    <td> {{$package->duration}} @lang('messages.month')</td>
                                    <td>
                                        <a class="btn btn-success" href="{{route('packages.edit' , $package->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                                            <i class="fa fa-eye"></i>
                                            @lang('messages.show')
                                        </button>
                                        <div class="modal fade" id="modal-info">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-info">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            @lang('messages.showPackageDetails')
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            @lang('messages.name') :
                                                            {{app()->getLocale() == 'ar' ? $package->name_ar : $package->name_en}}
                                                        </p>
                                                        <p>
                                                            @lang('messages.discounted_price') :
                                                            {{$package->discounted_price}}
                                                        </p>
                                                        <p>
                                                            @lang('messages.price') :
                                                            {{$package->price}}
                                                        </p>
                                                        <p>
                                                            @lang('messages.branch_price') :
                                                            {{$package->branch_price}}
                                                        </p>
                                                        <p>
                                                            @lang('messages.package_duration') :
                                                            {{$package->duration}}
                                                        </p>
                                                        <p>
                                                            @lang('messages.details') :
                                                            {!! app()->getLocale() == 'ar' ? $package->description_ar : $package->description_en !!}
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
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
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
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_city', function() {
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
                }, function() {

                    {{--var url = '{{ route("imageProductRemove", ":id") }}';--}}

                        {{--url = url.replace(':id', id);--}}

                        window.location.href = "{{ url('/') }}" + "/admin/packages/delete/"+id;


                });

            });

        });
    </script>

@endsection
