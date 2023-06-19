@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.edit_pay_online')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.edit_pay_online') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('myfatoora_token')}}">
                                @lang('messages.edit_pay_online')
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
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.edit_pay_online') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('myfatoora_token.update')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.payment_company') </label>
                                    <select name="payment_company" class="form-control">
                                        <option disabled selected> @lang('messages.choose_payment_company') </option>
                                        <option value="myFatoourah" {{$restaurant->payment_company == 'myFatoourah' ? 'selected' : ''}}>@lang('messages.myFatoourah')</option>
                                        <option value="tap"  {{$restaurant->payment_company == 'tap' ? 'selected' : ''}}>@lang('messages.tap')</option>
                                        <option value="express"  {{$restaurant->payment_company == 'express' ? 'selected' : ''}}>@lang('messages.express')</option>
                                    </select>
                                    @if ($errors->has('payment_company'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_company') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="online_token" style="display: {{($restaurant->payment_company == 'tap' || $restaurant->payment_company == 'myFatoourah') ? 'block':'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.online_token') </label>
                                        <input name="online_token" type="text" class="form-control"
                                               value="{{$restaurant->online_token}}"
                                               placeholder="@lang('messages.online_token')">
                                        @if ($errors->has('online_token'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('online_token') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div id="express_keys" style="display: {{$restaurant->payment_company == 'express' ? 'block' : 'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.merchant_key') </label>
                                        <input name="merchant_key" type="text" class="form-control"
                                               value="{{$restaurant->merchant_key}}"
                                               placeholder="@lang('messages.merchant_key')">
                                        @if ($errors->has('online_token'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('merchant_key') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.express_password') </label>
                                        <input name="express_password" type="text" class="form-control"
                                               value="{{$restaurant->express_password}}"
                                               placeholder="@lang('messages.express_password')">
                                        @if ($errors->has('express_password'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('express_password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.enable_reservation_online_pay') </label>
                                    <select name="enable_reservation_online_pay" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="true" {{$restaurant->enable_reservation_online_pay == 'true' ? 'selected' : ''}}>{{ trans('messages.yes') }}</option>
                                        <option value="false" {{$restaurant->enable_reservation_online_pay == 'false' ? 'selected' : ''}}>{{ trans('messages.no') }}</option>
                                    </select>
                                    @if ($errors->has('enable_reservation_online_pay'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('enable_reservation_online_pay') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- fees --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.online_payment_fees') </label>
                                    <input type="number" step="0.01" name="online_payment_fees" value="{{$restaurant->online_payment_fees}}" id="online_payment_fees" min="0.01" class="form-control" placeholder="@lang('dashboard.entry.online_payment_fees')">
                                    @if ($errors->has('online_payment_fees'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('online_payment_fees') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <!-- /.card-body -->
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
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(function () {
            $('select[name=payment_company]').on('change', function () {
                if ($(this).val() == 'express') {
                    document.getElementById('express_keys').style.display = 'block';
                    document.getElementById('online_token').style.display = 'none';
                } else {
                    document.getElementById('online_token').style.display = 'block';
                    document.getElementById('express_keys').style.display = 'none';
                }
            });
        });
    </script>
@endsection
