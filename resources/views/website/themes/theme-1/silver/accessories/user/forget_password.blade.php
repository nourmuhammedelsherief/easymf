@include('website.'.session('theme_path').'silver.layout.header')

{{--<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>--}}


<!-- header and footer bar go here-->
<div class="header header-fixed header-auto-show header-logo-app">
    <a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="header-title header-subtitle">
        <i class="fas fa-home"></i>
    </a>

    {{--        <a href="#" data-menu="menu-map" class="header-title header-subtitle"><i class="fas fa-map-marker-alt"></i> فرع الدمام </a>--}}

    @if($restaurant->ar == 'true' && $restaurant->en == 'true')
        @if(app()->getLocale() == 'en')
            <a href="#" class="header-title header-subtitle" onclick="window.location='{{ route('language' , 'ar') }}'">
                ع
            </a>
            {{--            <a type="button" onclick="window.location='{{ route('language' , 'ar') }}'">ع</a>--}}
        @else
            <a href="#" class="header-title header-subtitle" onclick="window.location='{{ route('language' , 'en') }}'">
                En
            </a>
            {{--            <button type="button" onclick="window.location='{{ route('language' , 'en') }}'">En</button>--}}

        @endif
    @endif


</div>


<div class="page-content pb-0">


    <style>
        .pinBox {
            --width: 296px;
            --height: 74px;
            --spacing: 47px;
            direction: ltr;
            display: inline-block;
            position: relative;
            width: var(--width);
            height: var(--height);
            background-image: url(https://i.stack.imgur.com/JbkZl.png);
        }

        .pinEntry {

            padding-left: 21px;
            font-family: courier, monospaced;
            font-size: 47px !important;
            height: var(--height);
            letter-spacing: var(--spacing);
            background-color: transparent;
            border: 0;
            outline: none;
            clip: rect(0px, calc(var(--width) - 21px), var(--height), 0px);
        }
    </style>



    <div class="row pt-2 mb-0">
        <div class="col-12">
            <div class="card mx-4">
                <div class="content mb-0">
                    <div class="text-center">
                        <h3 class=" mb-4"> @lang('messages.reset_password') </h3>

                    </div>

                    <form method="post" action="{{route('user_forget_password' , $restaurant->id)}}">
                        @csrf
                        <div class="input-style input-style-1">
                            <label class="font-14 font-600">@lang('messages.phone_number')</label>
                            <input class="form-control" type="text" name="phone_number" placeholder="@lang('messages.phone_number')">
                        </div>
                        <button type="submit" class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-dark2-dark">
                            @lang('messages.send_code')
                        </button>

                    </form>
                </div>
            </div></div>

    </div>

    @include('website.'.session('theme_path').'silver.layout.footer')

</div>

<!-- footer and footer card-->
<!-- end of page content-->
@include('website.'.session('theme_path').'silver.layout.scripts')
