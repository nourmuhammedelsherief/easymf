@extends('restaurant.authAdmin.master')
@section('style')
<style>
        .login-logo .change-lang{
            position: absolute;
            top: 24px;
            left: 17px;
            font-size: 1rem;
            font-weight: bold;
        }
</style>
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{url('restaurant/locale/' . (app()->getLocale() == 'ar' ? 'en' : 'ar'))}}" class="change-lang" style="{{app()->getLocale() == 'en' ? 'left:unset;right:17px !important;top:24px !important;' : ''}}">{{app()->getLocale() == 'ar' ?  'English' : 'عربي'}}</a>
            <a href="{{route('restaurant.login')}}"><b>@lang('messages.restaurant_login')</b></a>
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
            @include('flash::message')
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ trans('messages.welcome_login_message') }}</p>
                <form action="{{route('restaurant.login.submit')}}" method="post">
                    @csrf
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
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="@lang('messages.password')">
                        @if ($errors->has('password'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('password') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        {{-- <div class="col-8">
                            <div class="icheck-primary">
                                <label for="remember">
                                    @lang('messages.remember_me')
                                </label>
                                <input type="checkbox" id="remember" checked>
                            </div>
                        </div> --}}

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block"> @lang('messages.signIn') </button>
                        </div>

                    </div>
                </form>
                {{--            <div class="social-auth-links text-center mb-3">--}}
                {{--                <p>- OR -</p>--}}
                {{--                <a href="#" class="btn btn-block btn-primary">--}}
                {{--                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook--}}
                {{--                </a>--}}
                {{--                <a href="#" class="btn btn-block btn-danger">--}}
                {{--                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+--}}
                {{--                </a>--}}
                {{--            </div>--}}

                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-1">
                            <a href="{{ route('restaurant.password.phone') }}"  class="forget-password">{{ trans('messages.forget_password') }}</a>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-0">
                            <a href="{{route('restaurant.step1Register')}}" class="text-center">
                                @lang('messages.registerAccount')
                            </a>
                        </p>
                    </div>
                </div>


            </div>

        </div>


        <p class="footer-copyright pb-3 mb-1 pt-0 mt-0 font-13 font-600" style="    text-align: center;">
           {{ trans('messages.made_love') }}
            <i class="fa fa-heart font-14 color-red1-dark" style="color:red;"></i>
            <a style="color: blue" href="{{url('/')}}">

                {{ trans('messages.at_easy_menu') }}
            </a>
        </p>

        <div class="row">
            <p>
                <a href="https://web.easymenu.site/" class="btn btn-success"> @lang('messages.home_page')</a>
            </p>
            <p>
                <a href="https://easymenu.site/restaurants/easyMenuu">
                    <img src="https://web.easymenu.site/wp-content/themes/tqnee/img/qr.png" data-src="https://web.easymenu.site/wp-content/themes/tqnee/img/qr.png" class="download lazy loaded" data-was-processed="true">
                </a>
                <span>
                    {{ trans('messages.qr_code_try') }}
                </span>
            </p>

            <p class="footer-copyright pb-3 mb-1 pt-0 mt-0 font-13 font-600" style="    text-align: center;">
                {{ trans('messages.qr_code_try2') }}
            </p>

        </div>
    </div>

@endsection
