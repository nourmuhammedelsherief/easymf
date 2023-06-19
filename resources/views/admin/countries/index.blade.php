@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.countries')
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
                    <h1>@lang('messages.countries')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('countries.index')}}"></a>
                            @lang('messages.countries')
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
                    <a href="{{route('countries.create')}}" class="btn btn-info">
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
                                        <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th> @lang('messages.name_ar') </th>
                                <th> @lang('messages.name_en') </th>
                                <th> @lang('messages.currency') </th>
                                <th> @lang('messages.country_code') </th>
                                <th> @lang('messages.cities') </th>
                                <th> @lang('messages.restaurants') </th>
                                <th> @lang('messages.packages') </th>
                                <th> @lang('messages.activity') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($countries as $country)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$country->name_ar}} </td>
                                    <td> {{$country->name_en}} </td>
                                    <td>
                                        @if(app()->getLocale() == 'ar')
                                            {{$country->currency_ar}}
                                        @else
                                            {{$country->currency_en}}
                                        @endif
                                    </td>
                                    <td> {{$country->code}} </td>
                                    <td>
                                        <a href="{{route('cities.index' , $country->id)}}" class="btn btn-success"> {{$country->cities->count()}} </a>
                                    </td>
                                    <td>
                                        <a href="{{route('country_restaurants' , $country->id)}}" class="btn btn-success"> {{$country->restaurants->count()}} </a>
                                    </td>
                                    <td>
                                        <a href="{{route('country_packages.index' , $country->id)}}" class="btn btn-primary"> {{$country->country_packages->count()}} </a>
                                    </td>
                                    <td>
                                        @if($country->active == 'true')
                                            <a class="btn btn-info" href="{{route('activeCountry' , [$country->id , 'false'])}}">
                                                @lang('messages.active')
                                            </a>
                                        @else
                                            <a class="btn btn-danger" href="{{route('activeCountry' , [$country->id , 'true'])}}">
                                                @lang('messages.notActive')
                                            </a>
                                        @endif
                                    </td>
                                    <td>

                                        <a class="btn btn-info" href="{{route('countries.edit' , $country->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>

                                        <a class="delete_data btn btn-danger" data="{{ $country->id }}" data_name="{{ $country->name_ar }}" >
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
        $( document ).ready(function () {
            $('body').on('click', '.delete_data', function() {
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
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/countries/delete/" + id;

                });

            });
        });
    </script>
@endsection
