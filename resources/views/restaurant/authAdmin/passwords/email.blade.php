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
                <form method="POST" action="{{ route('restaurant.password.email') }}">
                    @csrf

                    <h3 class="font-green">@lang('messages.forgetPassword')</h3>
                    <p> @lang('messages.emailPasswordReset')</p>
                    <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="@lang('messages.email')" name="email" value="{{ old('email') }}" required autofocus />
                        @if ($errors->has('email'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('email') }}</span>
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
