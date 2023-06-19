@include('website.'.session('theme_path').'silver.layout.header')
{{--@include('website.'.session('theme_path').'silver.layout.head')--}}

<?php  $order_price = 0; $discount_value = 0; $is_percentage = 'false'; ?>
<style>
    body {
        position: relative;
    }

    .header-card {
        z-index: 1;
        top: -40px;
    }
</style>
<div class="card header-card shape-rounded" style="min-height:100px;" data-card-height="100">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg"></div>
</div>

<div class="card mr-0 ml-0 rounded-l" style="margin-top:40px;">

    @if($orders->count() > 0)
        <h4 class="mb-1 mt-0 text-center" style="z-index: 99;"> @lang('messages.order_review')</h4>
        <div class="content mt-0 pt-1">
            <div class="pb-5">
                @if($branch->main == 'true')
                    <a href="{{route('sliverHome' , $restaurant->name_barcode)}}"
                       class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
                       style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                @else
                    <a href="{{url('/restaurnt/'.$restaurant->name_barcode.'/'.$branch->name_barcode)}}"
                       class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
                       style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                @endif
            </div>
            @include('flash::message')
            <div class="row mb-0 pt-3 mt-5 mr-1 ml-1 py-3" style="border: 1px dashed #f7b538;">
                <div class="col-6 mb-1  mt-n4  text-right ">
                <span class="bg-white   font-16 font-700 color-theme">
                    {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}}
                </span>
                </div>
                @foreach($orders as $order)
                    <div class="col-12 mb-1  mt-n3  text-left ">
                        <a href="{{route('removeSilverCartOrder' , $order->id)}}"
                           class="font-13 color-gray2-dark pr-2">@lang('messages.delete')</a>
                    </div>
                    <div class="col-12 mb-1 mt-2">
                        <div class="mb-1">
                            <label for="size3-fac-radio" class="color-dark1-dark font-13">
                                <span class="minw100 font-14 font-700 color-theme">
                                    {{app()->getLocale() == 'ar' ? $order->product->name_ar : $order->product->name_en}}

                                    @if(isset($order->product_size->id))
                                        <span class="product-size">({{ trans('messages.product_size') }} : {{$order->product_size->name}})</span>
                                    @endif
                                </span>
                                x {{$order->product_count}}
                            </label>
                            <h3 class="font-11 float-left mr-2 mt-2">
                                {{$order->order_price}}
                                {{app()->getLocale() == 'ar' ? $order->product->branch->country->currency_ar : $order->product->branch->country->currency_en}}
                            </h3>
                        </div>
                    </div>
                    @if($order->silver_order_options->count() > 0)
                        <div class="col-12 mb-1">
                            <p class="font-14 font-700 color-theme">
                                @lang('messages.options')
                            </p>
                        </div>
                        @foreach($order->silver_order_options as $option)
                            <div class="col-12 mb-1 mt-2">
                                <div class="mb-1">
                                    <label for="size3-fac-radio" class="color-dark1-dark font-13">
                                        <span class="minw100 font-13 color-gray2-dark">
                                            {{app()->getLocale() == 'ar' ? $option->option->name_ar : $option->option->name_en}}
                                        </span>
                                        x {{$option->quantity}}</label>
                                    <h3 class="font-11 float-left mr-2 mt-2">
                                        {{$option->option->price}}
                                        {{app()->getLocale() == 'ar' ? $order->product->branch->country->currency_ar : $order->product->branch->country->currency_en}}
                                    </h3>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if($order->notes != null)
                        <div class="mb-1">
                            <span class="minw100 font-13">
                                @lang('messages.notes')
                            </span>
                            <p>
                                {{$order->notes}}
                            </p>
                        </div>
                    @endif

                    <hr style="width: 100%;"/>
                    @if($order->order_type == 'delivery' || ($order->order_type == 'previous' && $order->previous_order_type == 'delivery'))
                        @php
                            $order_type = 'delivery';
                            $delivery_price = $restaurant->delivery_price;
                        @endphp
                    @else
                        @php
                            $order_type = 'takeaway';
                            $delivery_price = 0;
                        @endphp
                    @endif
                    <?php
                    $order_price += $order->order_price;
                    if ($order->discount_id) {
                        $discount_value += $order->discount_value;
                        if ($order->discount->is_percentage == 'true') {
                            $is_percentage = 'true';
                        }
                    }
                    ?>
                @endforeach
            </div>


            <div class="divider mt-4 mb-2"></div>
            <div class="row mb-0 pr-4 pl-4">
                <div class="col-4">
                    <p class="font-14 font-700 color-theme">
                        @lang('messages.order_value')
                    </p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-13 font-600  color-theme">
                        {{$order_price}}
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>
                </div>
                @if($order_type == 'delivery')
                    <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                    <div class="col-4">
                        <p class="font-14 font-700 color-theme">
                            @lang('messages.delivery_value')
                        </p>
                    </div>
                    <div class="col-8 text-left">
                        <p class="font-13 font-600  color-theme">
                            {{$delivery_price}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        </p>
                    </div>
                @endif
                <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                @if($branch->tax == 'true')
                    <div class="col-4">
                        <p class="font-14 font-700 color-theme">@lang('messages.tax')</p>
                    </div>
                    <div class="col-8 text-left">
                        <p class="font-13 font-600 color-theme">
                            {{$branch->tax_value * ($order_price + $delivery_price) / 100}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        </p>
                    </div>
                    <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                @endif
                @if($discount_value > 0)
                    <div class="col-4">
                        <p class="font-14 font-700 color-theme">@lang('messages.discount')</p>
                    </div>
                    <div class="col-8 text-left">
                        <p class="font-13 font-600 color-theme">
                            {{$discount_value}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        </p>
                    </div>
                    <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                @endif

                <div class="col-4">
                    <p class="font-14 font-700 color-theme">@lang('messages.total')</p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-13 font-600 color-theme">
                        @php
                            $totalPrice = $order_price + $delivery_price;
                        @endphp
                        @if($branch->tax == 'true')
                            @if($is_percentage == 'true')
                                {{($totalPrice - $discount_value) + (($branch->tax_value * ($totalPrice - $discount_value)) / 100)}}
                            @else
                                {{($totalPrice - $discount_value) + (($branch->tax_value * $totalPrice) / 100)}}
                            @endif
                        @else
                            {{$totalPrice - $discount_value}}
                        @endif
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>
                </div>

            </div>
            <br>
            <div class="text-center">
                <a href="{{route('emptySilverCart')}}"
                   class="btn btn-l bg-highlight rounded-sm shadow-xl text-uppercase font-900">@lang('messages.finish_order')</a>
            </div>
        </div>
    @endif

</div>
@if($orders->count() == 0)
    <!--   <div class="card mr-0 ml-0 rounded-l">
        <div class="card header-card shape-rounded" style="min-height:100px;" data-card-height="100">
            <div class="card-overlay bg-highlight opacity-95"></div>
            <div class="card-overlay dark-mode-tint"></div>
            <div class="card-bg"></div>
        </div>
-->
    <div class="pb-5">
        @if($branch->main == 'true')
            <a href="{{route('sliverHome' , $restaurant->name_barcode,null , $branch->name_barcode)}}"
               class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
               style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                <i class="fa fa-chevron-left"></i>
            </a>
        @else
            <a href="{{url('/restaurnt/'.$restaurant->name_barcode.'/'.$branch->name_barcode)}}"
               class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
               style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                <i class="fa fa-chevron-left"></i>
            </a>
        @endif

    </div>
    <br>
    <br>
    <br>
    <h2 class="text-center"> @lang('messages.no_orders') </h2>
    <br>
    <br>
    <br>
    </div>
@endif
@include('website.'.session('theme_path').'silver.layout.footer')
@include('website.'.session('theme_path').'silver.layout.scripts')
