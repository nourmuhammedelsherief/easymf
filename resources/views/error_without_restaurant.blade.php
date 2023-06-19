@include('website.'.session('theme_path').'silver.layout.header')

<div id="page">
    <!-- header and footer bar go here-->
{{--    @include('website.'.session('theme_path').'silver.layout.head')--}}
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
                        <h4 class="text-uppercase color-white">خطأ</h4>
                        <strong class="alert-icon-text">
                            @lang('messages.restaurantNotActive')
                        </strong>
                        <button type="button" class="close color-white opacity-60 font-16" data-dismiss="alert" aria-label="Close">&times;</button>
                    </div>



                </div>
            </div>

        </div>


    </div>



    <!-- footer and footer card-->
</div>
<!-- end of page content-->
<!----menu-prodact -------------------->



<!-----------menu-profile---------------------------->
@include('website.'.session('theme_path').'silver.accessories.profile')
{{--@include('website.'.session('theme_path').'silver.accessories.user.login')--}}


@include('website.'.session('theme_path').'silver.layout.scripts')
