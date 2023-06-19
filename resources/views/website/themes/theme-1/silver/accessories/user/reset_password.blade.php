@include('website.'.session('theme_path').'silver.layout.header')
<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <!-- header and footer bar go here-->
    <div class="header header-fixed header-auto-show header-logo-app">
        <a href="{{route('sliverHome' , \App\Models\Restaurant::find($res)->name_barcode)}}" class="header-title header-subtitle">
            <i class="fas fa-home"></i>
        </a>
        <a href="#" class="header-title header-subtitle"> EN </a>


    </div>


    <div class="page-content pb-0">
        <div class="card   mb-0 pb-0">
            <div class="card-body p-1">

                <div class="card  mb-0">



                    <div class="row pt-2 mb-0">
                        <div class="col-12">
                            <div class="card mx-4">
                                <div class="content mb-0">
                                    <div class="text-center">
                                        <h3 class=" mb-4"> @lang('messages.reset_password') </h3>

                                    </div>

                                    <form method="post" action="{{route('user_reset_password' , [$user->id , $res])}}">
                                        @csrf
                                        <div class="input-style input-style-1">
                                            <label class="font-14 font-600">@lang('messages.password')</label>
                                            <input class="form-control" type="password" name="password"
                                                   placeholder="@lang('messages.password')">
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="input-style input-style-1">
                                            <label class="font-14 font-600">@lang('messages.password_confirmation')</label>
                                            <input class="form-control" type="password" name="password_confirmation"
                                                   placeholder="@lang('messages.password_confirmation')">
                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-dark2-dark">@lang('messages.confirm')</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>
            </div>

        </div>

        @include('website.'.session('theme_path').'silver.layout.footer')

    </div>
    <!-- footer and footer card-->
</div>
<!-- end of page content-->

@include('website.'.session('theme_path').'silver.layout.scripts')
