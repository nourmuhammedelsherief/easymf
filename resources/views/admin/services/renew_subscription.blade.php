@extends('admin.lteLayout.master')
@section('title')
    @lang('messages.add') @lang('dashboard.services_store')
@endsection

@section('styles')

@endsection

@section('content')
    {{--    @include('flash::message')--}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>  @lang('dashboard.services_store') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('/admin/home')}}">
                                @lang('dashboard.services_store')
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
                            <h3 class="card-title"> @lang('dashboard.services_store') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('admin.services_store.subscription' , $subscription->id)}}"
                              method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.payment_method') </label>
                                    <select name="payment_method" class="form-control" onchange="showDiv(this)"
                                            required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="bank"> @lang('messages.bank_transfer') </option>
                                        <option value="online"> @lang('messages.online') </option>
                                    </select>
                                    @if ($errors->has('payment_method'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group" id="hidden_div" style="display: none;">
                                    <label class="control-label"> @lang('messages.payment_type') </label>
                                    <select name="payment_type" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="visa"> @lang('messages.visa') </option>
                                        <option value="mada"> @lang('messages.mada') </option>
                                        <option value="apple_pay"> @lang('messages.apple_pay') </option>
                                    </select>
                                    @if ($errors->has('payment_type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_type') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.seller_code') </label>
                                    <input type="text" name="seller_code" class="form-control"
                                           value="{{old('seller_code')}}"
                                           placeholder="{{app()->getLocale() == 'ar' ? 'أذا لديك كود خصم أكتبه هنا' : 'Put Your Seller Code Here'}}">
                                    @if ($errors->has('seller_code'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('seller_code') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.confirm')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script>
        function showDiv(element) {
            if (element.value == 'online') {
                document.getElementById('hidden_div').style.display = element.value == 'online' ? 'block' : 'none';
            } else if (element.value == 'bank') {
                document.getElementById('hidden_div').style.display = element.value == 'bank' ? 'none' : 'none';
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('submit', 'form', function () {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
