@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.loyalty_points')
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
                    <h1>@lang('dashboard.loyalty_points')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
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
                        <form role="form" id="post-form" action="{{route('restaurant.loyalty_point.setting')}}" method="post"
                            enctype="multipart/form-data">
                          <input type='hidden' name='_token' value='{{Session::token()}}'>
                          
                          <div class="card-body">
                            <div class="form-group">
                                
                                <label class="control-label"> @lang('dashboard.entry.enable_loyalty_point') </label>
                                
                                <select name="enable_loyalty_point" id="" class="form-control">
                                    <option value=""></option>
                                    <option value="true" {{$restaurant->enable_loyalty_point == 'true' ? 'selected' : ''}}>{{trans('dashboard.yes')}}</option>
                                    
                                    <option value="false"{{$restaurant->enable_loyalty_point == 'false' ? 'selected' : ''}}>{{trans('dashboard.no')}}</option>
                                </select>
                            

                            </div>
                            <div class="form-group">
                                
                                <label class="control-label"> @lang('dashboard.entry.enable_loyalty_point_paymet_method') </label>
                                
                                <select name="enable_loyalty_point_paymet_method" id="" class="form-control">
                                    <option value=""></option>
                                    <option value="true" {{$restaurant->enable_loyalty_point_paymet_method == 'true' ? 'selected' : ''}}>{{trans('dashboard.yes')}}</option>
                                    
                                    <option value="false"{{$restaurant->enable_loyalty_point_paymet_method == 'false' ? 'selected' : ''}}>{{trans('dashboard.no')}}</option>
                                </select>
                            

                            </div>
                          <!-- /.card-body -->

                          <div class="card-footer">
                              <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                          </div>

                      </form>
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

                    window.location.href = "{{ url('/') }}" + "/restaurant/feedback/branch/delete/" + id;

                });

            });
        });
    </script>
@endsection

