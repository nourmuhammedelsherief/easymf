@extends('restaurant.authAdmin.master')

@section('title')
    @lang('messages.forgetPassword')
@endsection
@section('content')
    <!-- BEGIN FORGOT PASSWORD FORM -->

    <div class="login-box">
        <div class="login-logo">
            {{--            <a href="{{route('restaurant.login')}}"><b>@lang('messages.forgetPassword')</b></a>--}}
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @include('flash::message')
                <form method="POST" action="{{ route('password_reset_restaurant_post' , $restaurant->id) }}">
                    @csrf
                    <h3 class="text-center"> @lang('messages.passwordReSet') </h3>
                    <br>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix" type="password"  placeholder="@lang('messages.password')" name="password" value="{{ old('password') }}" required  />
                        @if ($errors->has('password'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('password') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix" type="password"  placeholder="@lang('messages.password_confirmation')" name="password_confirmation" value="{{ old('password_confirmation') }}" required  />
                        @if ($errors->has('password_confirmation'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('password_confirmation') }}</span>
                            </div>
                        @endif
                    </div>


                    <div class="form-actions">
                        <button type="submit" class="btn btn-success uppercase pull-right">@lang('messages.send')</button>
                    </div>
                </form>

            </div>

        </div>
        <p class="footer-copyright pb-3 mb-1 pt-0 mt-0 font-13 font-600" style="    text-align: center;">{{trans('messages.made_love')}}    <i class="fa fa-heart font-14 color-red1-dark" style="color:red;"></i>
            {{ trans('messages.at_easy_menu') }}</p>

    </div>

@endsection
