@include('website.'.session('theme_path').'silver.layout.header')

<div id="page">
    <!-- header and footer bar go here-->
    @include('website.'.session('theme_path').'silver.layout.head')
    @include('flash::message')

    <div id="footer-bar" class="footer-bar-5">
        <div class="clear"></div>
    </div>

    <div class="page-content pb-0">




        <div class="card   mb-0 pb-0">
            <div class="card-body p-1">

                <div class="card  mt-5 mb-0">



                    <div class="alert mr-3 ml-3 rounded-s bg-red2-dark" role="alert">
                        <span class="alert-icon"><i class="fa fa-exclamation-triangle font-18"></i></span>
                        <h4 class="text-uppercase color-white">
{{--                            @lang('messages.error')--}}
                        </h4>
                        <strong class="alert-icon-text">
                            @if($restaurant->archive == 'true' || $branch->archive == 'true')
                                @lang('messages.restaurantArchived')
                            @elseif($restaurant->archive == 'true' || $branch->stop_menu == 'true')
                                المنيو متوقف الأن
                            @else
                                @lang('messages.restaurantNotActive')
                            @endif
                        </strong>
                        <button type="button" class="close color-white opacity-60 font-16" data-dismiss="alert" aria-label="Close">&times;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('website.'.session('theme_path').'silver.layout.footer')
<!-- footer and footer card-->
</div>
<!-- end of page content-->
<!----menu-prodact -------------------->


<!----menu-map -------------------->
<div id="menu-map"
     class="menu menu-box-bottom menu-box-detached"
     data-menu-height="300"
     data-menu-effect="menu-over">


    <div class="content mb-0">
        <div class="float-right mt-n1 mr-3"><a href="#"
                                               class="icon icon-xs rounded-xl color-white bg-highlight close-menu"><i
                    class="fa fa-arrow-down"></i></a></div>
        <h3 class="font-700 text-center"> @lang('messages.branches')</h3>
        <div class="divider"></div>
        @include('website.'.session('theme_path').'silver.accessories.branches')
    </div>

</div>

<!-----------menu-profile---------------------------->
@include('website.'.session('theme_path').'silver.accessories.profile')
{{--@include('website.'.session('theme_path').'silver.accessories.user.login')--}}


@include('website.'.session('theme_path').'silver.layout.scripts')
