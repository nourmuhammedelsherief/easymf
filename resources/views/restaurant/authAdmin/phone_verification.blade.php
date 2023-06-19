
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('restaurant.phone_verification' , $restaurant->id)}}"><b>@lang('messages.phone_verification')</b></a>
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
                <p class="login-box-msg">Sign in to start your session</p>
                <form id="verification-form" action="{{route('restaurant.phone_verification', $restaurant->id)}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-code"></span>
                            </div>
                        </div>
                        <input type="number" name="code" class="form-control" required placeholder="@lang('messages.put_code')">
                     
                        @if ($errors->has('code'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ $errors->first('code') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-8">

                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block"> @lang('messages.confirm') </button>
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

                <p class="mb-1">
                    <a href="{{ route('restaurant.resend_code' , $restaurant->id) }}"  class="forget-password">@lang('messages.not_get_code')</a>
                </p>

            </div>

        </div>
    </div>

