@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.reservation_service')
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
                    <h1>@lang('dashboard.reservation_service')</h1>
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
                        <form role="form" id="post-form" action="{{route('restaurant.reservation.service.setting')}}" method="post"
                            enctype="multipart/form-data">
                          <input type='hidden' name='_token' value='{{Session::token()}}'>
                          
                          <div class="card-body">
                            <div class="form-group">
                                
                                <label class="control-label"> @lang('dashboard.entry.is_active') </label>
                                
                                <select name="reservation_service" id="" class="form-control">
                                    <option value=""></option>
                                    <option value="true" {{$restaurant->reservation_service == 'true' ? 'selected' : ''}}>{{trans('dashboard.yes')}}</option>
                                    
                                    <option value="false"{{$restaurant->reservation_service == 'false' ? 'selected' : ''}}>{{trans('dashboard.no')}}</option>
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


