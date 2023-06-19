@extends('admin.lteLayout.master')
@section('title')
    @lang('messages.branches')
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
                    <h1>
                        @lang('messages.branches')
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('reports.branches' , [$year , $month , $type])}}">
                                @lang('messages.branches')
                            </a>
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
                                        <input type="checkbox" class="group-checkable"
                                               data-set="#sample_1 .checkboxes"/>
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th>@lang('messages.name')</th>
                                <th>@lang('messages.country')</th>
                                <th>@lang('messages.restaurant')</th>
                                <th>@lang('messages.register_date')</th>
                                <th>@lang('messages.package')</th>
                                <th>@lang('messages.views')</th>
                                <th>@lang('messages.products')</th>
                                <th>@lang('messages.link')</th>
                                <th>@lang('messages.operations')</th>
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
                                        @if(app()->getLocale() == 'ar')
                                            {{$branch->name_ar}}
                                        @else
                                            {{$branch->name_en}}
                                        @endif
                                    </td>

                                    <td>
                                        @if($branch->country != null)
                                            @if(app()->getLocale() == 'ar')
                                                {{$branch->country->name_ar}}
                                            @else
                                                {{$branch->country->name_en}}
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($branch->restaurant != null)
                                            @if(app()->getLocale() == 'ar')
                                                {{$branch->restaurant->name_ar}}
                                            @else
                                                {{$branch->restaurant->name_en}}
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        {{$branch->created_at->format('Y-m-d')}}
                                    </td>
                                    <td>
                                        @if($branch->subscription != null)
                                            {{app()->getLocale() == 'ar' ? $branch->subscription->package->name_ar : $branch->subscription->package->name_en}}
                                        @endif
                                    </td>
                                    <td> {{$branch->views}} </td>
                                    <td> {{$branch->products->count()}} </td>
                                    <td>
                                        @if($branch->status != 'not_active')
                                            <?php $name = $branch->main == 'true' ? $branch->restaurant->name_barcode : ($branch->name_barcode == null ? $branch->name_en : $branch->name_barcode) ?>
                                            <a target="_blank"
                                               href="{{url('/restaurnt/'.$branch->restaurant->name_barcode.'/' . $name)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($branch->archive == 'true')
                                            <a class="btn btn-info"
                                               href="{{route('ArchiveBranch' , [$branch->id , 'false'])}}"> @lang('messages.remove_archive')</a>
                                        @else
                                            <a class="btn btn-secondary"
                                               href="{{route('ArchiveBranch' , [$branch->id , 'true'])}}"> @lang('messages.archive')</a>
                                        @endif

                                        <a class="btn btn-info"
                                           href="{{route('editRestaurantBranch' , $branch->id)}}">
                                            <i class="fa fa-edit"></i> @lang('messages.edit')
                                        </a>
                                        <a class="delete_city btn btn-danger" data="{{ $branch->id }}"
                                           data_name="{{ $branch->name_ar }}">
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>
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
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_city', function () {
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
                }, function () {

                    {{--var url = '{{ route("imageProductRemove", ":id") }}';--}}

                        {{--url = url.replace(':id', id);--}}

                        window.location.href = "{{ url('/') }}" + "/admin/branches/delete/" + id;
                });
            });
        });
    </script>

@endsection
