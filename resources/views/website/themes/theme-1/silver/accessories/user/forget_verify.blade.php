
@include('website.'.session('theme_path').'silver.layout.header')

<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <!-- header and footer bar go here-->
    <div class="header header-fixed header-auto-show header-logo-app">
        <a href="{{route('sliverHome' , \App\Models\Restaurant::find($res)->name_barcode)}}" class="header-title header-subtitle">
            <i class="fas fa-home"></i>
        </a>
        {{--        <a href="#" data-menu="menu-map" class="header-title header-subtitle"><i class="fas fa-map-marker-alt"></i> فرع الدمام </a>--}}

        <a href="#" class="header-title header-subtitle"> EN </a>


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



        <div class="card   mb-0 pb-0">
            <div class="card-body p-1">

                <div class="card  mb-0">



                    <div class="row pt-2 mb-0">
                        <div class="col-12">
                            <div class="card mx-4">
                                <div class="content mb-0">
                                    <div class="text-center">
                                        <h3 class=" mb-4"> @lang('messages.enterCode') </h3>

                                    </div>
                                    <form method="post" action="{{route('user_forget_verify' , [$user->id , $res])}}">
                                        @csrf
                                        <div class="d-flex">
                                            <div class="pinBox">
                                                <input class="pinEntry" name="code" type="number" max="9999"  pattern="[0-9]*" inputmode="numeric"  maxlength=4 >
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-dark2-dark">@lang('messages.confirm')</button>
                                    </form>


                                </div>
                            </div></div>

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
