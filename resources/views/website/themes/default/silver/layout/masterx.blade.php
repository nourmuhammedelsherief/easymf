@php
    if(isset($restaurant->id)){
        $pageTitle = app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en;
        $pageIcon = asset('uploads/restaurants/logo/' . $restaurant->logo);
    }

@endphp
@include('website.'.session('theme_path').'silver.layout.header')
<!--<div id="preloader" style="text-align:center;">
    <div class="lds-ripple"><div></div><div></div></div>
</div>-->
<style>

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function () {
        $('.scrolling-pagination').jscroll({
            autoTrigger: true,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function () {
                $('ul.pagination').remove();
            }
        });
    });
</script>
<div id="page" >
    <!-- header and footer bar go here-->
    @include('website.'.session('theme_path').'silver.layout.head')
    @include('flash::message')

    <div id="footer-bar" class="footer-bar-5">
        <div class="clear"></div>
        @if($branch->cart == 'true')
            @if($table != null)
                @php
                    $cartLink = route('tableGetCart', [$branch->id , $table->id]);
                    $cartCount =   \App\Models\TableOrderItem::with('product' , 'table_order')
                         ->whereHas('product', function ($q) use ($branch) {
                            $q->where('branch_id', $branch->id);
                         })
                         ->whereHas('table_order', function ($q) use ($table) {
                            $q->where('status' , 'in_reservation');
                            $q->where('ip' , \Illuminate\Support\Facades\Session::getId());
                            $q->where('table_id' , $table->id);
                         })->count();
                @endphp
            @endif

            @php
                $checkOrderService = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                                        ->whereIn('service_id' , [5 , 6 , 7 , 9 , 10])
                                        ->whereIn('status', ['active' , 'tentative'])
                                        ->first();
            @endphp
            @if(auth('web')->check() and ($branch->foodics_status == 'true' && $table == null) || ($checkOrderService == null && $table == null))
                @php
                    $cartLink = route('silverGetCart', $branch->id);
                    $cartCount =    \App\Models\SilverOrder::with('product')
                            ->whereHas('product', function ($q) use ($branch) {
                            $q->where('branch_id', $branch->id);
                                })
                                ->whereUserId(\Illuminate\Support\Facades\Auth::guard('web')->user()->id)
                                ->where('status' , 'in_cart')
                            ->count();
                @endphp
                {{-- <a href="{{route('silverGetCart', $branch->id)}}"
                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons }} !important">
                    <span>
                        {{
                            \App\Models\SilverOrder::with('product')
                            ->whereHas('product', function ($q) use ($branch) {
                            $q->where('branch_id', $branch->id);
                                })
                                ->whereUserId(\Illuminate\Support\Facades\Auth::guard('web')->user()->id)
                                ->where('status' , 'in_cart')
                            ->count()
                        }}
                    </span>
                    <i class="fa fa-shopping-cart"
                       style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></i>
                </a> --}}
            @elseif(auth('web')->check() and $checkOrderService and $branch->foodics_status == 'false' and $table == null)
                @php
                    $cartLink = route('goldGetCart', $branch->id);
                    $cartCount =   \App\Models\OrderItem::with('product' , 'order')
                                 ->whereHas('product', function ($q) use ($branch) {
                                    $q->where('branch_id', $branch->id);
                                 })
                                 ->whereHas('order', function ($q){
                                    $q->where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id);
                                    $q->where('status' , 'in_reservation');
                                 })->count();
                @endphp
                {{-- <a href="{{route('goldGetCart', $branch->id)}}"
                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons }} !important">
                    <span>
                        {{
                            \App\Models\OrderItem::with('product' , 'order')
                                ->whereHas('product', function ($q) use ($branch) {
                                    $q->where('branch_id', $branch->id);
                                })
                                ->whereHas('order', function ($q){
                                    $q->where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id);
                                    $q->where('status' , 'in_reservation');
                                })->count()
                        }}
                    </span>
                    <i class="fa fa-shopping-cart"
                       style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></i>
                </a> --}}
            @endif

        @endif
    </div>

    <div class="page-content pb-0">
        @php
            $sliderType = 'slider';

            $sliders = $restaurant->sliders()->orderBy('created_at' , 'desc')->get();
            foreach($sliders as $item):
                if($item->type == 'local_video'):
                    $sliderType = $item->type;
                    $sliderVideo = $item;
                    break;
                elseif($item->type == 'youtube'):
                    $sliderType = $item->type;
                    $sliderVideo = $item;
                    break;
                endif;
            endforeach;
        @endphp
        @if($sliderType == 'slider')
            <div class="single-slider mainsld owl-carousel">
                @include('website.'.session('theme_path').'silver.accessories.slider')
            </div>
        @elseif($sliderType == 'local_video')
            <video src="{{asset($sliderVideo->photo)}}" controls autoplay style="width:100%;max-height:210px;margin-bottom:80px;">
                <source src="{{asset($sliderVideo->photo)}}" type="video/mp4">
            </video>
        @elseif($sliderType == 'youtube')
            <div id="slider-{{$sliderVideo->id}}" style="margin-bottom:80px;">

            </div>
        @endif




        <div class="content">
            <div class="d-flex mt-n5 site-title">
                <div class="mt-n5">
                    @if($table == null)
                        @if($branch->main == 'true')
                            <a href="{{url('/restaurants/'.$restaurant->name_barcode)}}" class=" shadow-xl ">
                                <img src="{{asset('/uploads/restaurants/logo/' . $restaurant->logo)}}"
                                     style="width: 90px; height:90px; position: relative; z-index: 1;border: 1px dashed #f7b538;"/>
                            </a>

                        @else
                            <a href="{{route('sliverHomeBranch', [$restaurant->name_barcode , $branch->name_barcode])}}"
                               class=" shadow-xl ">
                                <img src="{{asset('/uploads/restaurants/logo/' . $restaurant->logo)}}"
                                     style="width: 90px; height:90px; position: relative; z-index: 1;border: 1px dashed #f7b538;"/>
                            </a>

                        @endif
                    @else
                        @if($table != null)
                            <a href="{{route('sliverHomeTableBranch' , [$restaurant->name_barcode , $table->foodics_id != null ? $table->foodics_id : $table->name_barcode])}}"
                               class=" shadow-xl ">
                                <img src="{{asset('/uploads/restaurants/logo/' . $restaurant->logo)}}"
                                     style="width: 90px; height:90px; position: relative; z-index: 1;border: 1px dashed #f7b538;"/>
                            </a>
                        @elseif($branch->main == 'true')
                            <a href="{{route('sliverHomeTable' , [$restaurant->name_barcode , $table->name_barcode])}}"
                               class=" shadow-xl ">
                                <img src="{{asset('/uploads/restaurants/logo/' . $restaurant->logo)}}"
                                     style="width: 90px; height:90px; position: relative; z-index: 1;border: 1px dashed #f7b538;"/>
                            </a>

                        @else
                            <a href="{{route('sliverHomeTableBranch' , [$restaurant->name_barcode , $table->name_barcode , $branch->name_barcode])}}"
                               class=" shadow-xl ">
                                <img src="{{asset('/uploads/restaurants/logo/' . $restaurant->logo)}}"
                                     style="width: 90px; height:90px; position: relative; z-index: 1;border: 1px dashed #f7b538;"/>
                            </a>
                        @endif
                    @endif

                    @if($branch->state == 'open')
                        <p class="text-center"
                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}"><i
                                class="fa fa-check-circle color-green1-dark "></i> @lang('messages.open')</p>
                    @elseif($branch->state == 'closed')
                        <p class="text-center"
                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                            <i class="fa fa-check-circle color-red"></i>
                            @lang('messages.closed')
                        </p>
                    @elseif($branch->state == 'busy')
                        <p class="text-center"
                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                            <i class="fa fa-check-circle color-dark"></i>
                            @lang('messages.busy')
                        </p>
                    @endif
                </div>
                <div class="align-self-center pr-3 mt-1 restaurant-title" style="line-height: 23px;">
                    <h5 class="font-600 font-18 mb-1"
                        style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                        {{app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en}}
                    </h5>
                    <span data-menu="menu-restaurant-description" class="color-theme font-400 font-14"
                          style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}} !important;">
                        {!! app()->getLocale() == 'ar' ? strip_tags(\Illuminate\Support\Str::limit($restaurant->description_ar,70)) : strip_tags(\Illuminate\Support\Str::limit($restaurant->description_en , 70)) !!}
                    </span>
                </div>

            </div>
            @if(isset($table->id))
                <h2 class="text-center"   style="margin-top:20px;color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}"> {{ trans('messages.table') }} : {{$table->name}}</h2>
            @endif
            @if($restaurant->socials->count() == 0 || $restaurant->socials->count() > 0 || $restaurant->deliveries->count() > 0 || $restaurant->sensitivities->count() > 0 || $restaurant->offers->count() > 0 || $restaurant->information_ar != null || $restaurant->information_en != null || $restaurant->res_branches->count() > 0)
                <div id="box" class=" mt-3 mb-n1" style="min-height: 100px;">

                    {{-- <div class="icon-user itemCatTop">
                        <a href="{{route('reservation.page1' , $branch->id)}}"
                           class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                           <i class="fas fa-calendar-alt"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"></i>
                        </a>
                        <p class="font-600 font-13 mt-2"
                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.booking')</p>
                    </div> --}}

                    @if($restaurant->enable_feedback == 'true'  )
                        <div class="icon-user itemCatTop">
                            <a href="#" data-menu="menu-rate"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="far fa-comment-alt"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.restaurant_feedback')</p>
                        </div>
                    @endif
                    @if($restaurant->is_call_phone == 'true')
                        <div class="icon-user itemCatTop">

                            <a href="tel:{{$restaurant->call_phone}}"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="fas fa-phone"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.call')</p>

                        </div>
                    @endif
                    @if($restaurant->is_whatsapp == 'true')
                        <div class="icon-user itemCatTop">

                            <a href="https://wa.me/{{$restaurant->whatsapp_number}}" target="__blank"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="fab fa-whatsapp"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.whatsapp')</p>

                        </div>
                    @endif
                    @if($restaurant->reservation_service == 'true' and empty($table))
                        <div class="icon-user itemCatTop">

                            <a href="{{route('reservation.page1' , $restaurant->id)}}"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="far fa-ticket-alt"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.reservations')</p>

                        </div>
                    @endif
                    @if( empty($table))
                        <div class="icon-user itemCatTop">
                            <a href="#"
                               data-menu="menu-share-list"

                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="far fa-share-alt"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.follow_us')</p>
                        </div>
                    @endif
                    @if($restaurant->deliveries->count() > 0)
                        <div class="icon-user itemCatTop">
                            <a href="#" data-menu="menu-delv-list"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="far fa-car"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.deliveries')</p>
                        </div>
                    @endif
                    @if($restaurant->sensitivities->count() > 0)
                        <div class="icon-user itemCatTop">
                            <a href="#" data-menu="menu-allergy-list"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="fab fa-firefox"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.sensitivities')</p>
                        </div>
                    @endif


                    @if($restaurant->offers->count() > 0)
                        <div class="icon-user itemCatTop">
                            <a href="#" data-menu="menu-photo-list"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="far fa-images"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.offers')</p>
                        </div>
                    @endif


                    @if($restaurant->information_ar != null || $restaurant->information_en != null)
                        <div class="icon-user itemCatTop">
                            <a href="#" data-menu="menu-info"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="far fa-info"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.information')</p>
                        </div>
                    @endif

                    @if($restaurant->res_branches->count() > 0 and empty($table))

                        <div class="icon-user itemCatTop">
                            <a href="#" data-menu="menu-mapsico-list"
                               class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
                                <i class="fas fa-map-marker-alt"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
                            </a>
                            <p class="font-600 font-13 mt-2"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.the_branches')</p>
                        </div>
                    @endif

                </div>
            @endif
        </div>


        @if((url()->current() == url('/restaurants/'.$restaurant->name_barcode) && $restaurant->menu == 'vertical' )||(url()->current() == url('/restaurnt/'.$restaurant->name_barcode.'/'.$branch->name_barcode) && $restaurant->menu == 'vertical'))
            @include('website.'.session('theme_path').'silver.accessories.categories_vertical')
        @else
            <div id="xcategories" class="">
                <div>
                    @include('website.'.session('theme_path').'silver.accessories.xcategories')
                </div>
                <div id="menu-sub-categories">
                    @include('website.'.session('theme_path').'silver.accessories.sub_categories')
                </div>

            </div>


            <div class="card xproducts   mb-0 pb-0"
                 style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->background }} !important">
                <div class="card-body p-1" id="restaurant-products">
                    @include('website.'.session('theme_path').'silver.accessories.products')
                </div>
            </div>
        @endif

        @if(isset($cartLink) and isset($cartCount) )
            <div id="cart-count" class="cart-count {{$cartCount == 0 ? 'hide' : '' }}">
                <a href="{{$cartLink}}" class="cart-btn" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}}">
                    <i class="fa fa-shopping-cart"></i>
                    [<span class="count">{{$cartCount}}</span>]
                    <span>{{ trans('messages.cart_count') }}</span>
                </a>
            </div>
        @endif
    </div>
    <style>
        body{
            @if(isset($cartCount) and $cartCount > 0)
margin-bottom: 50px !important;
        @endif
}
    </style>
    <!-- footer and footer card-->
    @include('website.'.session('theme_path').'silver.layout.footer')
</div>
<!-- end of page content-->

<!-----------menu-profile---------------------------->
@include('website.'.session('theme_path').'silver.accessories.profile')


@include('website.'.session('theme_path').'silver.accessories.res_branches')
@include('website.'.session('theme_path').'silver.accessories.related')
@include('website.'.session('theme_path').'silver.layout.scripts')

