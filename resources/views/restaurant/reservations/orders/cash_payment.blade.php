@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.cash_on_delivery')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('dashboard.cash_on_delivery') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.reservation.index')}}">
                                @lang('messages.reservations')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('resetaurant.reservation.services')}}">
                                @lang('dashboard.reservation_services')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.cash_on_delivery') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('resetaurant.reservation.cash-settings' )}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.enable_reservation_cash') </label>
                                    <select name="enable_reservation_cash" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="true" {{$restaurant->enable_reservation_cash == 'true' ? 'selected' : ''}}>{{ trans('messages.yes') }}</option>
                                        <option value="false" {{$restaurant->enable_reservation_cash == 'false' ? 'selected' : ''}}>{{ trans('messages.no') }}</option>
                                    </select>
                                    @if ($errors->has('enable_reservation_cash'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('enable_reservation_cash') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <!-- /.card-body -->
                            {{-- @method('PUT') --}}
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
