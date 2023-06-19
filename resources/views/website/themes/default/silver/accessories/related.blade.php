

<div id="menu-prodact-100"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-bottom menu-box-detached rounded-lp"
     data-menu-load="{{route('loadMenuProduct' , 100)}}"
     data-menu-height="100%"
     data-menu-effect="menu-over">
</div>


<div id="menu-restaurant-description"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-med menu-box-detached rounded-l"
     data-menu-height="auto"
     data-menu-effect="menu-over">

    <p class="color-theme font-400 font-14 px-3 py-3">
        {!! app()->getLocale() == 'ar' ? $restaurant->description_ar : $restaurant->description_en !!}
    </p>
</div>

@include('website.'.session('theme_path').'silver.accessories.ads_popup')


<!----menu-map -------------------->
<div id="menu-map"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-bottom menu-box-detached"
     data-menu-height="300"
     data-menu-effect="menu-over">


    <div class="popup-content mb-0">
        <div class="popup-header" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
            <div class="float-right mt-n1 mr-3">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                    <i class="fa fa-arrow-down" ></i>
                </a>
            </div>
            <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                @lang('messages.branches')
            </h3>
            <div class="divider"></div>
        </div>
        @include('website.'.session('theme_path').'silver.accessories.branches')
    </div>
</div>

<!----menu-info -------------------->
<div id="menu-info"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-bottom menu-box-detached rounded-lp"
     data-menu-height="400"
     data-menu-effect="menu-over">


    <div class="content mb-0">
        <div class="float-right mt-n1 mr-3">
            <a href="#"
               style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
               class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                <i class="fa fa-arrow-down"></i>
            </a>
        </div>
        <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">@lang('messages.information')</h3>
        <div class="divider"></div>
        @include('website.'.session('theme_path').'silver.accessories.information')

    </div>
</div>

<!------------------->
<!------------------->
<!--Menu Share List-->
<!------------------->
<!------------------->
<div id="menu-share-list"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-bottom menu-box-detached rounded-m"
     data-menu-height="310"
     data-menu-width="320"
     data-menu-effect="menu-over">

    <div class="popup-content mb-0">
        <div class="popup-header" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
            <div class="float-right mt-n1 mr-3">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                    <i class="fa fa-arrow-down" ></i>
                </a>
            </div>
            <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}"> @lang('messages.follow_us')</h3>
            <h2 class="text-left btn-share"
                style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;">
                {{-- <a class="a2a_dd icon-button share" href="https://www.addtoany.com/share"
                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;">
                    <i class="fa fa-share-alt"></i>
                </a> --}}
                <script>
                    var a2a_config = a2a_config || {};
                    a2a_config.onclick = 1;
                    a2a_config.locale = "ar";
                </script>
                <script async src="https://static.addtoany.com/menu/page.js"></script>
            </h2>
            <div class="divider"></div>
        </div>
        @include('website.'.session('theme_path').'silver.accessories.follow_us')
    </div>
</div>
{{-- rate link --}}
<div id="menu-rate-link"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     data-menu="menu-rate-link"
     class="menu menu-box-bottom menu-box-detached rounded-m"
     data-menu-height="310"
     data-menu-width="320"
     data-menu-effect="menu-over">

    <div class="content mb-0">
        <div class="float-right mt-n1 mr-3">
            <a href="#"
               style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
               class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                <i class="fa fa-arrow-down" ></i>
            </a>
        </div>
        <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}"> @lang('messages.restaurant_feedback')</h3>

        <div class="divider"></div>
        <h2 class="text-center " style="margin-top: 40px">{{ trans('messages.rate_question') }}</h2>
        <div class="row buttons" style="max-width: 200px; margin: auto; margin-top:30px;">
            <div class="col-6">
                <a href="#" target="_blank" class="btn btn-primary yes" class="btn btn-primary float-right" style="font-size: 0.8rem;" rel="noopener noreferrer">{{ trans('messages.yes') }}</a>
            </div>
            <div class="col-6">
                <button href="#" target="_blank" class="btn btn-secondary no"  class="btn btn-secondary float-left" style="font-size: 0.8rem" >{{ trans('messages.no') }}</button>
            </div>
        </div>

    </div>
</div>
{{-- Rate us --}}
@if(isset($restaurant->id))
    <div id="menu-rate"
         style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
         class="menu menu-box-bottom menu-box-detached rounded-m"
         {{-- data-menu-height="310" --}}
         data-menu-width="320"
         data-menu-effect="menu-over">

        <div class="popup-content mb-0">
            <div class="popup-header" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
                <div class=" mt-n1 mr-3">
                    <a href="#"
                       style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                       class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                        <i class="fa fa-arrow-down" ></i>
                    </a>
                </div>
                <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}"> @lang('messages.restaurant_feedback')</h3>
                <h2 class="text-left"
                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;">
                    {{-- <a class="a2a_dd icon-button share" href="https://www.addtoany.com/share"
                       style="margin:10px;color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;">
                        <i class="fa fa-share-alt"></i>
                    </a> --}}
                    <script>
                        var a2a_config = a2a_config || {};
                        a2a_config.onclick = 1;
                        a2a_config.locale = "ar";
                    </script>
                    <script async src="https://static.addtoany.com/menu/page.js"></script>
                </h2>
                <div class="divider"></div>
            </div>
            @include('website.'.session('theme_path').'silver.accessories.rate_us')
        </div>
    </div>
@endif
<!------------------->
<!------------------->
<!--Menu delv List-->
<!------------------->
<!------------------->
<div id="menu-delv-list"
     class="menu menu-box-bottom menu-box-detached rounded-m"
     data-menu-height="280"
     data-menu-width="320"
     data-menu-effect="menu-over"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">

    <div class="popup-content mb-0">
        <div class="popup-header" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
            <div class=" mt-n1 mr-3">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                    <i class="fa fa-arrow-down"></i>
                </a>
            </div>
            <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">@lang('messages.deliveries')</h3>
            <div class="divider"></div>
        </div>
        @include('website.'.session('theme_path').'silver.accessories.delivery')
    </div>
</div>

<div id="menu-photo-list"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-bottom menu-box-detached rounded-m"
     data-menu-height="550"
     data-menu-width="320"
     data-menu-effect="menu-over">
    @include('website.'.session('theme_path').'silver.accessories.offers')
</div>

<div id="data-photo-show"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu  ad-menu menu-box-bottom menu-box-detached rounded-m"
     {{-- data-menu-height="550" --}}
     data-menu-width="320"
     data-menu-effect="menu-over">

    @include('website.'.session('theme_path').'silver.accessories.offer_show')
</div>
<div id="menu-allergy-list"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
     class="menu menu-box-bottom menu-box-detached rounded-m"
     data-menu-height="350"
     data-menu-width="320"
     data-menu-effect="menu-over">

    <div class="popup-content mb-0">
        <div class="popup-header" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
            <div class=" mt-n1 mr-3">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu"><i
                        class="fa fa-arrow-down" ></i>
                </a>
            </div>
            <h3 class="font-700 text-center" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}"> @lang('messages.sensitivities')</h3>
            <div class="divider"></div>
        </div>
        @include('website.'.session('theme_path').'silver.accessories.sensitivities')
    </div>
</div>

@push('scripts')
    <script>
        $(function(){

            $('#menu-rate-link .buttons .btn').on('click' , function(){
                var tag = $(this);
                console.log($('#menu-rate-link .close-menu'));
                if(tag.hasClass('yes')){
                    var url  = tag.attr('href');
                    console.log(url);
                    window.location.replace(url);
                }else{
                    $('#menu-rate-link .close-menu').trigger('click');
                    window.location.reload();
                }


            });
        });
    </script>
@endpush

