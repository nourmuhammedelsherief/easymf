@extends('restaurant.authAdmin.master')

@section('title')
    @lang('messages.passwordReSet')
@endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('restaurant.login')}}"><b>@lang('messages.forgetPassword')</b></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <form method="POST" action="{{ route('restaurant.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <h3 class="font-green">@lang('messages.passwordReSet')</h3>
                    <div class="form-group">
                        <input id="email" class="form-control placeholder-no-fix{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" autocomplete="off" placeholder="@lang('messages.email')" name="email" value="{{ old('email') }}" required autofocus/>
                        @if ($errors->has('email'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('email') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">@lang('messages.password')</label>
                        <input id="password" class="form-control placeholder-no-fix{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" autocomplete="off"  placeholder="@lang('messages.password')" name="password" required />
                        @if ($errors->has('password'))

                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('password') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">@lang('messages.password_confirmation')</label>
                        <input id="password-confirm" class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="@lang('messages.password_confirmation')" name="password_confirmation" required /> </div>

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
