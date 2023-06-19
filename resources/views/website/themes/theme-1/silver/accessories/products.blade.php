<div class="card products  mb-0"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->background }} !important">
    <script>
        $('ul.pagination').hide();
        $(function () {
            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function () {
                    $('ul.pagination').remove();
                    $('[data-src]').lazy();
                }
            });
        });
    </script>

    <div class=" prodcontent">
        @if($products->count() > 0)
            <div class="scrolling-pagination">
                @foreach($products as $productIndex => $product)
                    @if($product->time == 'true')
                        <?php
                        $carbon = \Carbon\Carbon::now();
                        $current_day = $carbon->format('l');
                        $checkCategoryDay = \App\Models\ProductDay::where('product_id', $product->id)->first();
                        $categoryDay = \App\Models\ProductDay::with('day')
                            ->whereHas('day', function ($q) use ($current_day) {
                                $q->where('name_en', $current_day);
                            })
                            ->where('product_id', $product->id)
                            ->first();
                        ?>
                        @if(($checkCategoryDay != null && $categoryDay != null) || $checkCategoryDay == null)
                            @if(check_time_between($product->start_at , $product->end_at))
                                <div data-url="{{route('product.show' , [$restaurant->name_barcode , $product->id , $table != null ? $table->id : ''])}}" 
                                    {{$restaurant->id == 1145 ? 'data-xmenu' : 'data-menu'}}="menu-prodact-{{$product->id}}"
                                class="prod {{ (!$products->hasMorePages() and $products->count() == ($productIndex + 1)) ? 'last-product' : '' }} d-flex mb-3 shadow-l rounded-m"
                                data-category_id="{{$product->menu_category_id}}"
                                style="max-height:150px; background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
                                <div class="pb-1 p-2 product-redirect " onclick="console.log('test');"  style=" width: calc(100% - 127px);" data-url="{{route('product.show' , [$restaurant->name_barcode , $product->id , $table != null ? $table->id : ''])}}" data-x-menu="menu-prodact-{{$product->id}}">
                                    <a href="{{$restaurant->id == 1145 ? route('product.show' , [$restaurant->name_barcode , $product->id  , $table != null ? $table->id : '']) : '#'}}" data-url="{{route('product.show' , [$restaurant->name_barcode , $product->id , $table != null ? $table->id : ''])}}" data-x-menu="menu-prodact-{{$product->id}}" class="link-to-product">
                                        <h5  class="font-15 product-name font-600 pb-1"
                                             style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                            {{app()->getLocale() == 'ar' ? ($product->name_ar == null ? $product->name_en:$product->name_ar) : ($product->name_en == null ? $product->name_ar : $product->name_en)}}
                                            @if($product->poster != null)
                                                <img data-src="{{asset('/uploads/posters/' . $product->poster->poster)}}"
                                                     height="30"
                                                     width="30" class="poster-image">
                                            @endif
                                        </h5>
                                    </a>
                                    <input type="hidden" id="btnClickedValue" name="btnClickedValue"
                                           value="{{$product->id}}"/>

                                    <p class="font-13 mb-1 link-to-product"
                                       style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">
                                        {!! app()->getLocale() == 'ar' ? strip_tags($product->description_ar) : strip_tags($product->description_en) !!}
                                    </p>
                                    <div class="d-flex link-to-product">
                                        {{-- @if($product->poster != null)
                                            <i>
                                                <img
                                                    data-src="{{asset('/uploads/posters/' . $product->poster->poster)}}"
                                                    height="40"
                                                    width="40">
                                            </i>
                                        @endif --}}
                                        <p class="mr-auto font-12 font-600 mb-0 ">

                                        <div class="oldprice font-600">
                                            @if($product->price_before_discount != null)
                                                <span
                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                    <del> {{$product->price_before_discount}}  {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}</del>
                                                </span>
                                            @endif
                                            @php
                                                $product_sizes = \App\Models\ProductSize::whereProductId($product->id)->get();
                                            @endphp
                                            @if($product->price != 0 && $product_sizes->count() == 0)
                                                <span
                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                        {{$product->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}
                                                    </span>
                                            @elseif($product->price != 0 && $product_sizes->count() > 0)
                                                <span
                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                        {{$product->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}},
                                                @foreach($product_sizes as $product_size)
                                                        {{$product_size->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}
                                                        ,
                                                    @endforeach
                                                    </span>
                                            @elseif($product->price == 0 && $product_sizes->count() > 0)
                                                <span
                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                @foreach($product_sizes as $product_size)
                                                        {{$product_size->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}
                                                        ,
                                                    @endforeach
                                                    </span>
                                            @endif

                                        </div>
                                        </p>
                                    </div>

                                </div>
                                <div class="mr-auto product-image"
                                     style=" overflow: hidden;   width: 127px;  text-align: center;  border-radius: 15px 0 0 15px;">
                                    @if($product->foodics_image != null)
                                        <img src="{{$product->foodics_image}}"
                                             class=" shadow-xl rounded-m"
                                             width="127" height="127" style="    ">
                                    @else

                                        <img
                                            data-src="{{empty($product->photo) ? asset($restaurant->image_path) : asset($product->image_path)}}"
                                            class=" shadow-xl rounded-m"
                                            width="127" height="127" style="    ">
                                    @endif
                                </div>
            </div>
        @endif
        @endif

        @else
            <div data-url="{{route('product.show' , [$restaurant->name_barcode , $product->id ,$table != null ? $table->id : ''])}}" {{$restaurant->id == 1145 ? 'data-xmenu' : 'data-menu'}}="menu-prodact-{{$product->id}}" class="prod {{ (!$products->hasMorePages() and $products->count() == ($productIndex + 1)) ? 'last-product' : '' }}  d-flex mb-3 shadow-l rounded-m"
            data-category_id="{{$product->menu_category_id}}"
            style="max-height:150px; background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
            <div class="pb-1 p-2   product-redirect" style="width: calc(100% - 127px);" data-url="{{route('product.show' , [$restaurant->name_barcode , $product->id , $table != null ? $table->id : ''])}}" data-x-menu="menu-prodact-{{$product->id}}">
                <a href="#" class="link-to-product" data-url="{{route('product.show' , [$restaurant->name_barcode , $product->id ,$table != null ? $table->id : ''])}}" data-x-menu="menu-prodact-{{$product->id}}">
                    <h5 class="font-15 product-name font-600 pb-1"
                        style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                        {{app()->getLocale() == 'ar' ? ($product->name_ar == null ? $product->name_en: $product->name_ar) : ($product->name_en == null ? $product->name_ar : $product->name_en)}}
                        @if($product->poster != null)
                            <img data-src="{{asset('/uploads/posters/' . $product->poster->poster)}}"
                                 height="30"
                                 width="30" class="poster-image">
                        @endif
                    </h5>
                    </h5>
                </a>
                <input type="hidden" id="btnClickedValue" name="btnClickedValue"
                       value="{{$product->id}}"/>

                <p class="font-13 mb-1 link-to-product"
                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">
                    {!! app()->getLocale() == 'ar' ? strip_tags($product->description_ar) : strip_tags($product->description_en) !!}
                </p>
                <div class="d-flex link-to-product">
                    {{-- @if($product->poster != null)
                        <i>
                            <img data-src="{{asset('/uploads/posters/' . $product->poster->poster)}}"
                                 height="40"
                                 width="40">
                        </i>
                    @endif --}}
                    @php
                        $product_sensitivities = \App\Models\ProductSensitivity::whereProductId($product->id)->get();
                    @endphp
                    @if($product_sensitivities->count() > 0)
                        @foreach($product_sensitivities as $product_sensitivity)
                            <i>
                                <img
                                    data-src="{{asset('/uploads/sensitivities/' . $product_sensitivity->sensitivity->photo)}}"
                                    height="25"
                                    width="25" class="sens-image">
                            </i>
                        @endforeach
                    @endif
                    <p class="mr-auto font-12 font-600 mb-0 ">
                    {{-- @if($product->calories != null)
                        <span class="pl-4"
                              style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                {{$product->calories}}
                <i class="fa fa-fire pr-1 color-red2-dark"></i>
            </span>
                @endif --}}
                    <div class="oldprice font-600">
                        @if($product->price_before_discount != null)
                            <span
                                style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                            <del> {{$product->price_before_discount}}  {{app()->getLocale() == 'ar' ? $product->branch->country->currency_ar : $product->branch->country->currency_en}}</del>
                                        </span>
                        @endif
                        @php
                            $product_sizes = \App\Models\ProductSize::whereProductId($product->id)->get();
                        @endphp
                        @if($product->price != 0 && $product_sizes->count() == 0)
                            <span
                                style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                        {{$product->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}
                                                    </span>
                        @elseif($product->price != 0 && $product_sizes->count() > 0)
                            <span
                                style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                        {{$product->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}},
                                                @foreach($product_sizes as $product_size)
                                    {{$product_size->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}
                                    ,
                                @endforeach
                                                    </span>
                        @elseif($product->price == 0 && $product_sizes->count() > 0)
                            <span
                                style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                @foreach($product_sizes as $product_size)
                                    {{$product_size->price}} {{app()->getLocale() == 'ar' ? $restaurant->country->currency_ar : $restaurant->country->currency_en}}
                                    ,
                                @endforeach
                                                    </span>
                        @endif

                    </div>
                    </p>
                </div>

            </div>
            <div class="mr-auto product-image"
                 style=" overflow: hidden;   width: 127px;  text-align: center;  border-radius: 15px 0 0 15px;">

                @if($product->foodics_image != null)
                    <img src="{{$product->foodics_image}}"
                         class=" shadow-xl rounded-m"
                         width="127" height="127" style="    ">
                @else
                    <img
                        data-src="{{empty($product->photo) ? asset($restaurant->image_path) : asset($product->image_path)}}"
                        class=" shadow-xl rounded-m"
                        width="127" height="127" style="    ">
                @endif
            </div>
    </div>
    @endif

    @if($table)
        <div id="menu-prodact-{{$product->id}}"
             class="menu  menu-box-bottom product-menu menu-box-detached "
             data-menu-load="{{route('loadMenuProduct' , [$product->id , $table->id])}}"
             data-menu-height="100%"

             data-menu-effect="menu-over"></div>
    @else
        <div id="menu-prodact-{{$product->id}}"
             class="menu menu-box-bottom product-menu menu-box-detached "
             data-menu-load="{{route('loadMenuProduct' , $product->id)}}"
        ></div>
    @endif
    @endforeach
    {{$products->links()}}


</div>
@else
    <br>
    <h3 style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;"
        class="text-center">@lang('messages.no_products')</h3>
    @endif
    </div>
    </div>
    <style>
        .product-image {
            background-color: #EEE;

        }
        .product-name{
            position: relative;
            padding-left: 30px;
        }
        [dir=ltr] .product-name{
            padding-left: 0;
            padding-right: 30px;
        }
        .product-name .poster-image{
            position: absolute;
            top: 3px;
            left: 2px;
        }
        .sens-image{
            border-radius: 100%;
            border:1px solid #CCC;
            box-shadow: 1px 1px 10px #CCC;
        }
    </style>
    <script>
        var checkIsMore = false;
        $(function () {

            $('[data-src]').lazy();
            console.log('here');
            @if($restaurant->enable_fixed_category == 'true')
            $('.prodcontent .prod[data-menu]').on('click'   ,function(){
                $('#xcategories').addClass('fixedIndex-2').removeClass('fixedIndex-3');
                $('#footer-bar').addClass('fixedIndex-2').removeClass('fixedIndex-3');
                console.log('category -x');
                
            });
            @endif
            $('.link-to-product').on('click' , function(){
                var item = $(this).parent().parent();
                if(item.data('xmenu') && item.data('url'))
                    window.location.replace(item.data('url'));
            });


        });
    </script>
