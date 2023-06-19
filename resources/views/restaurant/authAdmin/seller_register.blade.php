@extends('restaurant.authAdmin.master')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>


    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    @if(app()->getLocale() == 'en')
        <link rel="stylesheet" href="{{asset('dist/css/style_ltr.css')}}">
    @endif

    <style>
        .error {
            color: red;
            display: block !important;
        }

        label {
            display: block;
            width: 100%;
            font-size: 15px;
        }

        .country-flag .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        .country-flag .select2-container .select2-selection--single {
            font-size: 13px;
            /* display: block; */

            height: 50px;
            line-height: 50px;
            margin-bottom: 10px;
            /* padding-right: 20px; */
            border: none;
            background-color: transparent;
            /* border: solid 1px #f7b538!important; */
            transition: all 250ms ease;
        }

        .country-flag .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 50px !important;
        }

        .country-flag .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px;
        }

        .form-control.x-input-style {
            font-size: 13px;
            display: block;
            width: 100%;
            height: 50px;
            line-height: 50px;
            margin-bottom: 10px;
            padding-right: 20px;
            border: none;
            background-color: rgb(0 0 0 / 7%);
            border: solid 1px #f7b538 !important;
            transition: all 250ms ease;
        }

        .mobile-number-col {
            position: relative;
        }

        .mobile-number-col .country-flag {
            width: 62px;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 9;
        }

        .mobile-number-col .form-control {
            padding-left: 62px;
            border: 1px solid #ced4da !important;
        }

        .mobile-number-col .country-flag .select2-container .select2-selection--single {
            padding: 0;
        }

        .mobile-number-col img.img-flag {
            width: 35px !important;
            float: right;
            margin-top: 9px;
            height: auto !important;
            margin-right: 7px;
            /* max-height: 35px !important; */
        }

        .mobile-number-col .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
        }

        p.message {
            font-size: 0.8rem;
            padding: 0;
            margin-bottom: 5px;
            text-align: right;
            width: 100%;
        }

        .mobile-code {
            display: none;
        }

        .display-none {
            display: none;
        }

        #photo-error {
            display: none !important;
        }

        [dir=rtl] label.error {
            text-align: right;
        }

        [dir=rtl] .alert-danger p {
            text-align: right;
        }

        p.message {
            font-size: 0.8rem;
            padding: 0;
            margin-bottom: 5px;
            text-align: right;
            width: 100%;
        }

        .mobile-code {
            display: none;
        }

        .toggle-content {
            display: none;
            padding: 2rem 1rem;
            background: #f1f1f1;
            text-align: right
        }

        .title_celeste {
            /*background: #111;*/
            /*color: #fff;*/
            padding: 1rem 0;
            cursor: pointer;
            margin: 0;
        }

        .title_celeste {
            text-align: right
        }

        .title_celeste i {
            transition: transform .3s ease;
            float: left;
        }

        .title_celeste.active i {
            transform: rotate(-180deg)
        }

        .questions-content {
            padding: 1.25rem;
        }

        .question-header {
            background-color: #e9ecef;
            height: 50px;;
        }

        form .input-group {
            padding: 0 1.25rem;
        }

        .summery {
            margin: 2rem 1.25rem;
        }

        .summery .row > div:first-child {
            text-align: right;
        }

        .total-price {
            border: 1px solid #CCC;
            border-radius: 10px;
            padding: 7px 0;
            margin-top: 20px;
        }

        .login-logo .change-lang {
            position: absolute;
            top: 0px;
            left: -8px;
            font-size: 1rem;
            font-weight: bold;
        }

    </style>
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{url('restaurant/locale/' . (app()->getLocale() == 'ar' ? 'en' : 'ar'))}}"
               class="change-lang">{{app()->getLocale() == 'ar' ? 'English' : 'عربي'}}</a>
            <a href="{{route('restaurant.step1Register')}}"><b>@lang('messages.restaurant_register')</b></a>
        </div>
        <div class="card">
            @if (session('An_error_occurred'))
                <div class="alert alert-success">
                    {{ session('An_error_occurred') }}
                </div>
            @endif
            @if (session('warning_login'))
                <div class="alert alert-danger">
                    {{ session('warning_login') }}
                </div>
            @endif
            <div class="alert alert-danger" style="display:none">
                @foreach($errors->all() as $temp)
                    <p>{{$temp}}</p>
                @endforeach
            </div>

            <div class="card-body login-card-body" style="padding:1.25rem 0;">
                <p class="login-box-msg">{{ trans('messages.welcome_login_message') }}</p>
                <form id="post-form" method="post" action="{{route('restaurant.submit_step1')}}" class="step1">
                    @csrf
                    @if(Request::is('restaurant/register-gold/step1'))
                        <input type="hidden" name="package" value="gold">
                    @endif
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success d-none" id="msg_div">
                                    <span id="res_message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 form-group">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="text" name="name_ar" value="{{old('name_ar')}}" class="form-control"
                                   placeholder="@lang('messages.restaurant_name_ar')">


                        </div>
                        @if ($errors->has('name_ar'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('name_ar') }}</span>
                            </div>
                        @endif
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="text" name="name_en" class="form-control" value="{{old('name_en')}}"
                                   placeholder="@lang('messages.restaurant_name_en')">

                            @if ($errors->has('name_en'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('name_en') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <input type="email" name="email" class="form-control" placeholder="@lang('messages.email')">

                            @if ($errors->has('email'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('email') }}</span>
                                </div>
                            @endif
                            <br>
                            <p class="message email"></p>
                        </div>

                        <div class="input-group mb-3 mobile-number-col">

                            {{-- <label class="font-14 font-600">@lang('messages.phone_number')</label> --}}
                            <div class="country-flag">
                                <select name="country_id" class="country">
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}"
                                                title="{{$country->flag == null ? null : asset($country->flag_path)}}"
                                                data_flag="@php

                                                    if($country->id == 1) echo '01xxxxxxxx';
                                                    elseif($country->code == 973) echo '3xxxxxxx';
                                                    elseif($country->id == 2) echo '05xxxxxxxx';
                                                    else echo $country->code . 'xxxxxxxx';

                                                @endphp">
                                            {{$country->code}} +
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <input class="form-control" type="number" name="phone_number" pattern="[0-9]*"
                                   inputmode="numeric" autocomplete="off"
                                   placeholder="@lang('messages.phone_number')">
                            @if ($errors->has('phone_number'))
                                <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                            </span>
                            @endif
                            <p class="message phone"></p>
                        </div>

                        {{-- <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-flag"></span>
                                </div>
                            </div>
                            <select class="form-control"  name="country_id">
                                <option disabled selected> @lang('messages.choose_country') </option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}">
                                        @if(app()->getLocale() == 'ar')
                                            {{$country->name_ar}}
                                        @else
                                            {{$country->name_en}}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('country_id'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('country_id') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                            <input type="text" name="phone_number"  class="form-control" placeholder="@lang('messages.phone_number')">

                            @if ($errors->has('phone_number'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('phone_number') }}</span>
                                </div>
                            @endif
                        </div> --}}
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <input type="password" name="password" class="form-control"
                                   placeholder="@lang('messages.password')">

                            @if ($errors->has('password'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('password') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="@lang('messages.password_confirmation')">

                            @if ($errors->has('password_confirmation'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('password_confirmation') }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- <h6 class="text-right"> {{app()->getLocale() == 'ar' ? 'أختر تصنيف' : 'Choose Category'}} </h6> --}}
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-sliders-h"></span>
                                </div>
                            </div>
                            <select class="select2 category category_id form-control" multiple="multiple"
                                    data-placeholder="{{trans('messages.choose_category')}}" name="category_id[]">
                                <option disabled> @lang('messages.choose_category') </option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">
                                        @if(app()->getLocale() == 'ar')
                                            {{$category->name_ar}}
                                        @else
                                            {{$category->name_en}}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('category_id'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('category_id') }}</span>
                                </div>
                            @endif
                        </div>
                        {{-- <h6 class="text-right"> {{app()->getLocale() == 'ar' ? ' المدينة' : 'Choose City'}} </h6> --}}

                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-flag"></span>
                                </div>
                            </div>
                            <select class="form-control" name="city_id">
                                <option disabled selected> @lang('messages.choose_city') </option>

                            </select>

                            @if ($errors->has('city_id'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('city_id') }}</span>
                                </div>
                            @endif
                        </div>

                        {{--                        <div class="input-group mb-3">--}}
                        {{--                            <div class="input-group-append">--}}
                        {{--                                <div class="input-group-text">--}}
                        {{--                                    <span class="fas fa-flag"></span>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                            <select class="form-control" name="package_id">--}}
                        {{--                                <option disabled selected> {{app()->getLocale() == 'ar' ? 'اختر الباقة' : 'Choose Package'}} </option>--}}
                        {{--                                @foreach($packages as $package)--}}
                        {{--                                    <option value="{{$package->id}}">--}}
                        {{--                                        @if(app()->getLocale() == 'ar')--}}
                        {{--                                            {{$package->name_ar}}--}}
                        {{--                                        @else--}}
                        {{--                                            {{$package->name_en}}--}}
                        {{--                                        @endif--}}
                        {{--                                    </option>--}}
                        {{--                                @endforeach--}}
                        {{--                            </select>--}}

                        {{--                            @if ($errors->has('package_id'))--}}
                        {{--                                <div class="alert alert-danger">--}}
                        {{--                                    <button class="close" data-close="alert"></button>--}}
                        {{--                                    <span> {{ $errors->first('package_id') }}</span>--}}
                        {{--                                </div>--}}
                        {{--                            @endif--}}
                        {{--                        </div>--}}
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-flag"></span>
                                </div>
                            </div>
                            <select class="form-control" name="answer_id">
                                <option disabled
                                        selected> {!! \App\Models\RegisterQuestion::find(1)->question_lang !!} </option>
                                <?php $answers = \App\Models\RegisterAnswers::all(); ?>
                                @foreach($answers as $answer)
                                    <option value="{{$answer->id}}">
                                        {!! $answer->answer_lang !!}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('answer_id'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('answer_id') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- end first step --}}


                    {{-- payment content --}}
                    <div id="payment-content" class="display-none">
                        <div class="question-header" style="margin-bottom:20px;"></div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-money"></span>
                                </div>
                            </div>
                            <select class="form-control" readonly name="payment_type">
                                <option disabled selected> {{ trans('messages.choose_payment') }}</option>
                                {{-- <option value="bank" >
                                    @lang('messages.transfer_bank')
                                </option> --}}
                                <option value="online" selected>{{ trans('messages.online') }}</option>
                            </select>

                            @if ($errors->has('payment_type'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('payment_type') }}</span>
                                </div>
                            @endif
                        </div>
                        {{-- bank_id --}}
                        <div class="input-group mb-3 bank-payment">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-university"></span>
                                </div>
                            </div>
                            <select class="form-control" name="bank_id">
                                <option disabled selected> {{ trans('messages.choose_bank') }}</option>
                                @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">
                                        @if(app()->getLocale() == 'ar')
                                            @lang('messages.bank') : {{$bank->name_ar}}
                                        @else
                                            @lang('messages.bank') : {{$bank->name_en}}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="description text-right"></p>
                        </div>

                        <label for=""
                               class="bank-photo bank-payment text-right">{{ trans('messages.transfer_photo') }}</label>
                        {{-- photo --}}
                        <div class="input-group mb-3 bank-payment">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-file-image"></span>
                                </div>
                            </div>
                            <input type="file" name="photo" id="" class="form-control" accept=".png,.jpg,.jepg">
                        </div>
                        {{-- online payment --}}
                        <div class="input-group mb-3 online-payment">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-dollar-sign"></span>
                                </div>
                            </div>
                            <select name="payment_method" class="form-control">
                                <option selected disabled>{{ trans('messages.payment_way') }}</option>
                                <option value="visa">{{ trans('messages.visa') }}</option>
                                <option value="mada">{{ trans('messages.mada') }}</option>
                                <option value="apple_pay">{{ trans('messages.apple_pay') }}</option>
                            </select>
                        </div>


                    </div>

                    <div class="summery">
                        <div class="row">
                            <div class="col-7">{{ trans('messages.subscription_price') }}</div>
                            <div class="col-5">700
                                {{-- {{number_format((float)($package_price + $tax_value) , 0, '.', '')}} --}}
                                 ريال</div>
                        </div>
                        {{-- @if($discount > 0)
                            <div class="row">
                                <div class="col-7">{{ trans('messages.discount_price') }} ({{$discount_percentage}}%)</div>
                                <div class="col-5">{{$discount}} ريال</div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-6">{{ trans('messages.tax') }} ({{$tax}}%)</div>
                            <div class="col-6">{{ trans('messages.amount') }}
                                ({{number_format((float)$tax_value, 2, '.', '')}} ريال)
                            </div>
                        </div> --}}

                        <div class="row total-price">
                            <div class="col-7">{{ trans('messages.total_price') }}
                            @if($discount > 0)
                                {{ trans('messages.after_discount' , ['discount' =>$discount_percentage]) }}
                            @endif
                            </div>
                            <div class="col-5">{{number_format((float)$finalPrice,0, '.', '')}} ريال</div>
                        </div>
                    </div>
                    <div class="mobile-code">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-code"></span>
                                </div>
                            </div>
                            <input type="number" name="code" class="form-control" required
                                   placeholder="@lang('messages.put_code')">

                            @if ($errors->has('code'))
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <span> {{ $errors->first('code') }}</span>
                                </div>
                            @endif
                        </div>
                        <p class="mb-3" style="text-align: right;
                        font-size: 14px;
                        margin-top: 21px;">
                            <a href="javascript:;" class="forget-password">@lang('messages.not_get_code')</a>
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <button type="submit" id="send_form"
                                    class="btn btn-primary btn-block"> {{ trans('messages.register') }} </button>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </form>


            </div>

            <div class="question-header"></div>
            <div class="questions-content">
                @php
                    $public_questions = \App\Models\PublicQuestion::all();
                @endphp
                @if($public_questions->count() > 0)
                    <h5 class="text-right"> @lang('messages.public_questions') </h5>
                    @foreach($public_questions as $public_question)
                        <p class="title_celeste">
                            {{$public_question->question_lang}}
                            <i class="fa fa-angle-down font18"></i>
                        </p>

                        <div class="toggle-content">
                            {!! $public_question->answer_lang !!}
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
        <p class="footer-copyright pb-3 mb-1 pt-0 mt-0 font-13 font-600" style="    text-align: center;">
            {{trans('messages.made_love')}}
            <i class="fa fa-heart font-14 color-red1-dark" style="color:red;"></i>
            <a style="color: blue" href="{{url('/')}}">

                {{ trans('messages.at_easy_menu') }}
            </a>
        </p>

    </div>

    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>

    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2.category').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
    <script>
        var restaurantId = 0;
        var countries = {!! json_encode($countries) !!};
        var registerStep = 'step1';

        function displayErrors(errors) {
            $.each(errors, function (key, all) {
                console.log(key);
                var item = $('.form-control.' + key);
                if (item) {
                    var count = 0;
                    $.each(all, function (k, v) {
                        // console.log(k +' :  ' +v);
                        if (count == 0) {
                            var error = $('#' + key + '-error');
                            if (error.length > 0) {
                                error.text(v);
                                console.log('exists');
                                console.log(error);
                            } else {
                                console.log('created');
                                var d = item.parent().append('<label id="' + key + '-error" class="error">' + v + '</label>');
                                console.log(item.parent());
                            }
                        }

                        count += 1;
                    });
                }

            });
        }

        $(function () {
            var input = $('input.form-control , select.form-control');
            $.each(input, function (k, value) {
                var item = $(value);
                item.addClass(item.prop('name'));
            });
        });
        if ($("#post-form.step1").length > 0 && registerStep == 'step1') {
            $("#post-form.step1").validate({
                rules: {
                    name_ar: {
                        required: true,
                        maxlength: 191,
                        // unique: true,
                    },
                    name_en: {
                        required: true,
                        maxlength: 191
                    },
                    // email: {
                    //     required: true,
                    //     maxlength: 191,
                    //     email: true,
                    // },
                    // phone_number: {
                    //     required: true,
                    //     maxlength: 11
                    // },
                    country_id: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8
                    },
                    photo: {
                        required: false,
                        mimetype: ['png', 'jpg']
                    }

                },
                messages: {
                    name_ar: {
                        required: "{{trans('messages.name_ar')}}" + " " + "{{trans('messages.required')}}",
                        maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.name_ar')}}" + "191",
                    },
                    name_en: {
                        required: "{{trans('messages.name_en')}}" + " " + "{{trans('messages.required')}}",
                        maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.name_en')}}" + "191",
                    },
                    email: {
                        required: "{{trans('messages.email')}}" + " " + "{{trans('messages.required')}}",
                        maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.email')}}" + "191",
                    },
                    country_id: {
                        required: "{{trans('messages.country')}}" + " " + "{{trans('messages.required')}}",
                    },
                    phone_number: {
                        required: "{{trans('messages.phone_number')}}" + " " + "{{trans('messages.required')}}",
                        maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.phone_number')}}" + "11",
                    },
                    password: {
                        required: "{{trans('messages.password')}}" + " " + "{{trans('messages.required')}}",
                        minlength: "{{trans('messages.min_length')}}" + " " + "{{trans('messages.password')}}" + "8",
                    },
                    password_confirmation: {
                        required: "{{trans('messages.password_confirmation')}}" + " " + "{{trans('messages.required')}}",
                        minlength: "{{trans('messages.min_length')}}" + " " + "{{trans('messages.password_confirmation')}}" + "8",
                    },

                },
                submitHandler: function (form) {
                    console.log('test step1');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (registerStep == 'step1') {
                        $('#send_form').html('Sending..');
                        $.ajax({
                            url: "{{route('restaurant.seller.register' , $seller->custom_url)}}",
                            type: "POST",
                            data: $('#post-form').serialize(),
                            success: function (response) {
                                console.log('home');
                                console.log(response);
                                if (response.errors && response.errors.length > 0) {

                                    $('#send_form').html('{{trans('messages.send')}}');
                                    jQuery('.alert-danger').html('');
                                    jQuery.each(response.errors, function (key, value) {
                                        displayErrors(response.errors);
                                    });
                                } else if (response.status == true) {
                                    $('#send_form').html('{{trans('messages.confirm_register')}}');
                                    toastr.success(response.message);
                                    restaurantId = response.data.restaurant_id;
                                    console.log('step1 ');
                                    $('.questions-content').fadeOut(200);
                                    $('.question-header').fadeOut(200);
                                    $('#post-form > .form-body .form-control').prop('disabled', true);
                                    $('.mobile-code').css('display', 'block');
                                    $('.mobile-code input[name=code]').focus();
                                    $('#post-form.step1').removeClass('step1').addClass('step2');
                                    registerStep = 'step2';
                                    $("html, body").animate({scrollTop: $(document).height()}, 1000);
                                    $('input[name=code]').css('box-shadow', '4px 4px 10px #ccc');
                                    // $('#res_message').show();
                                    // $('#res_message').html(response.msg);
                                    // $('#msg_div').removeClass('d-none');

                                    // document.getElementById("post-form").reset();
                                    // setTimeout(function(){
                                    //     $('#res_message').hide();
                                    //     $('#msg_div').hide();
                                    // },10000);
                                    // window.location=response.url;
                                    $('#post-form').validate().destroy();
                                    $('#post-form').submit(function () {
                                        return false;
                                    });
                                    console.log('done');
                                } else if (response.status == false) {
                                    $('#send_form').html('{{trans('messages.send')}}');
                                    displayErrors(response.errors);
                                }
                            }, error: function (xhr) {
                                console.log(xhr);
                                $('#send_form').html('{{trans('messages.send')}}');
                                // displayErrors(xhr.errors);
                            }
                        });

                    }

                }
            })
        } else {

        }


        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            // console.log(state);
            // console.log(state.element.attributes.data_flag.value);
            if (state.title.length > 10) {
                var image = '<img width="30" height="30" src="' + state.title + '" class="img-flag" />';
            } else var image = '<span>' + state.text + '</span>';
            var $state = $(
                image
            );
            return $state;
        };

        function formatState2(state) {
            if (!state.id) {
                return state.text;
            }
            // console.log(state);
            // console.log(state.element.attributes.data_flag.value);
            if (state.title.length > 10) {
                var image = '<img width="30" height="30" src="' + state.title + '" class="img-flag" />';
            } else var image = '<span>' + state.text + '</span>';
            var $state = $(
                image
            );

            console.log(state.element.attributes.data_flag.value);
            $('.mobile-number-col input[name=phone_number]').prop('placeholder', state.element.attributes.data_flag.value);

            return $state;
        };
        var checktimeout = null;
        var banks = {!! json_encode($banks) !!};
        $(function () {
            $('select[name=country_id]').on('change', function () {
                var content = '';
                var tag = $(this);
                $.each(countries, function (key, country) {
                    if (country.id == tag.val()) {
                        content += '<option disabled selected> @lang('messages.choose_city') </option>';
                        $.each(country.cities, function (k, city) {
                            content += '<option value="' + city.id + '">' + city.name + '</option>';
                        });
                        $('select[name=city_id]').html(content);
                        // $('select[name=city_id]').select2();
                    }
                });
            });
            $('select[name=country_id]').trigger('change');
            $("select.country").select2({
                templateResult: formatState,
                templateSelection: formatState2,
                // dir: "rtl",
                dropdownAutoWidth: true,
                dropdownParent: $('.country-flag')

            });
            $('input[name=email]').on('keyup', function () {
                var tag = $(this);
                if (checktimeout) clearTimeout(checktimeout);
                checktimeout = setTimeout(function () {

                    $.ajax({
                        url: "{{route('restaurant.check')}}",
                        method: 'get',
                        data: {email: tag.val()},
                        headers: {
                            accept: 'application/json'
                        },
                        success: function (json) {
                            console.log(json);
                            if (json.status == true) {
                                $('p.message.email').removeClass('text-danger').addClass('text-success').text('{{trans("messages.email_success")}}');
                            } else if (json.status == false) {
                                $('p.message.email').addClass('text-danger').removeClass('text-success').text(json.data.email);
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr);
                            $('p.message.email').addClass('text-danger').removeClass('text-success').text('');
                        }
                    });
                }, 1000);
            });
            $('input[name=phone_number], select[name=country_id]').on('keyup change', function () {
                var tag = $('input[name=phone_number]');
                if (checktimeout) clearTimeout(checktimeout);
                checktimeout = setTimeout(function () {
                    var countryId = tag.parent().find('select[name=country_id]').val();
                    $.ajax({
                        url: "{{route('restaurant.check')}}",
                        method: 'get',
                        data: {phone_number: tag.val(), country_id: countryId},
                        headers: {
                            accept: 'application/json'
                        },
                        success: function (json) {
                            console.log(json);
                            if (json.status == true) {
                                $('p.message.phone').removeClass('text-danger').addClass('text-success').text('{{trans("messages.phone_success")}}');
                            } else if (json.status == false) {
                                $('p.message.phone').addClass('text-danger').removeClass('text-success').text(json.data.phone_number);
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr);
                        }
                    });
                }, 1000);
            });

            // forget code
            $('body').on('click', 'a.forget-password', function () {
                var tag = $(this);
                console.log(restaurantId);
                $.ajax({
                    url: "{{url('restaurant/resend_code')}}/" + restaurantId,
                    method: 'GET',
                    headers: {accept: 'application/json'},
                    success: function (json) {
                        console.log(json);
                        if (json.status == true) {
                            toastr.success(json.message);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            });
            $('select[name=payment_type]').trigger('change');
            // verification phone
            $('body').on('click', '#post-form.step2 button', function () {
                console.log('test');
                var form = $('#post-form');
                var formData = new FormData(form[0]);

                $.ajax({
                    url: "{{route('restaurant.seller.register' , $seller->custom_url)}}/verification-code/" + restaurantId,
                    method: "POST",
                    headers: {Accept: 'application/json'},
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (json) {
                        $('#send_form').html("{{trans('messages.pay_now')}}");
                        console.log(json);

                        if (json.status == true) {
                            registerStep = 'step3';
                            toastr.success(json.message);
                            $('#payment-content').fadeIn(300);
                            form.addClass('step3').removeClass('step2');
                            $('.mobile-code').fadeOut(600);
                            $('select[name=payment_type]').trigger('change');
                            $("html, body").animate({scrollTop: $(document).height()}, 500);
                            // window.location.replace(json.url);
                        } else {
                            toastr.info(json.message);
                        }
                    },
                    error: function (xhr) {
                        $('#send_form').html("{{trans('messages.pay_now')}}");
                        console.log(xhr);
                        toastr.error('لم يتم التحقق من الكود');
                    }
                });
            });

            // bank show description
            $('select[name=bank_id]').on('change', function () {
                var tag = $(this);
                var lang = "{{app()->getLocale()}}";
                $.each(banks, function (key, value) {

                    if (value.id == tag.val()) {

                        tag.parent().find('.description').html('@lang('messages.bank') : ' + (lang == 'ar' ? value.name_ar : value.name_en) + ' ,<br>\
							@lang('messages.account_number') : ' + value.account_number + ' ,<br>\
							@lang('messages.IBAN_number') : ' + value.IBAN_number);
                    }
                });
            });
            $('select[name=bank_id]').trigger('change');

            $('select[name=payment_type]').on('change', function () {
                var tag = $(this);
                if (tag.val() == 'bank') {
                    $('.bank-payment').fadeIn(300);
                    $('.online-payment').fadeOut(300);
                } else if (tag.val() == 'online') {
                    $('.bank-payment').fadeOut(300);
                    $('.online-payment').fadeIn(300);
                } else {
                    $('.bank-payment').fadeOut(300);
                    $('.online-payment').fadeOut(300);
                }
            });
            $('select[name=payment_type]').trigger('change');

            $('body').on('click', '#post-form.step3 button', function () {
                console.log('hi step3');
                var form = $('#post-form');
                if ($('select[name=payment_type]').val() == 'online') {
                    console.log('online');
                    var myForm = $('#post-form-online');
                    var url = "{{route('restaurant.seller.register' , $seller->custom_url)}}/payment/" + restaurantId + "?payment_type=online&payment_method=" + $('select[name=payment_method]').val();
                    window.location.replace(url);
                    // myForm.prop('action' , "{{route('restaurant.seller.register' , $seller->custom_url)}}/payment/" + restaurantId);
                    // form.prop('action' , "{{route('restaurant.seller.register' , $seller->custom_url)}}/payment/" + restaurantId);
                    // var token = $('#post-form ');
                    // myForm.append(token);
                    // myForm.submit();
                } else {
                    var formData = new FormData(form[0]);

                    $.ajax({
                        url: "{{route('restaurant.seller.register' , $seller->custom_url)}}/payment/" + restaurantId,
                        method: "POST",
                        headers: {Accept: 'application/json'},
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function (data) {
                            $('#send_form').html('إرسال ...');
                        },
                        success: function (json) {
                            $('#send_form').html("{{trans('messages.confirm')}}");
                            console.log(json);

                            if (json.status == true) {
                                registerStep = 'step4';
                                toastr.success(json.message);
                                // $('#payment-content').fadeIn(300);

                                window.location.replace(json.url);
                            } else {
                                toastr.info(json.message);
                            }
                        },
                        error: function (xhr) {
                            $('#send_form').html("{{trans('messages.confirm')}}");
                            console.log(xhr);
                            toastr.error('خطاء في البيانات .. يرجي إعادة كتابة البيانات بشكل صحيح');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $('.title_celeste').on("click", function (evt) {
            evt.preventDefault();

            let _this = $(this),
                ct = _this.next('.toggle-content');

            // check if clicked title has active class
            if (_this.hasClass('active')) {

                _this.removeClass('active');
                ct.slideUp();

            } else {
                $('.title_celeste.active').removeClass('active');
                $('.toggle-content').slideUp();
                _this.addClass('active');
                ct.slideDown();

            }

        });
    </script>
@endsection

