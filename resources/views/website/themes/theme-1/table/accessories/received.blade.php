@include('website.'.session('theme_path').'silver.layout.header')
<div id="preloader">
    <div class="spinner-border color-highlight" role="status"></div>
</div>

<div id="page">


    <div class="card header-card shape-rounded" data-card-height="100">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg"></div>


    </div>

    <div class="pb-5">

        @if($branch->main == 'true')
            <a href="{{route('sliverHomeTable' , [$restaurant->name_barcode , $table->name_barcode])}}"
               class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
               style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                <i class="fa fa-chevron-left"></i>
            </a>
        @else
            <a href="{{route('sliverHomeTableBranch' , [$restaurant->name_barcode , $table->name_barcode , $branch->name_barcode])}}"
               class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
               style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                <i class="fa fa-chevron-left"></i>
            </a>
        @endif


    </div>

    <div class="card mr-0 ml-0 rounded-l">


        <p class="mb-1 mt-4 text-center"><img src="{{asset('images/success.gif')}}" style="max-width: 100px;"/></p>


        <div class="content mt-0 pt-1">


            <div class="row mb-0 mr-3 ml-3 py-3" style="border: 2px dashed #f7b538; background: #f5f5f5;">
                <div class="col-12 mb-1 text-center">
                    <p class="font-16 font-700 color-theme">
                        @lang('messages.order_number')
                    </p>
                </div>
                <div class="col-12 mb-2 text-center">
                    <p class="font-16 font-700 color-theme">
                        {{$order->id}}
                    </p>
                </div>

                <div class="col-4 mb-1">
                    <p class="font-15 font-700 color-theme"> @lang('messages.order_date') </p>
                </div>
                <div class="col-8 mb-1 text-left">
                    <p class="font-14 font-600  color-theme">
                        {{$order->created_at->format('Y-m-d')}}
                    </p>
                </div>

                {{--                <div class="col-4 mb-1"><p class="font-15 font-700 color-theme">رقم الطاولة</p></div>--}}
                {{--                <div class="col-8 mb-1 text-left"><p class="font-14 font-600 color-theme"> 27</p></div>--}}

                <div class="col-4 mb-1">
                    <p class="font-15 font-700 color-theme">
                        @lang('messages.order_type')
                    </p>
                </div>
                <div class="col-8 mb-1 text-left">
                    <p class="font-14 font-600  color-theme">
                        @lang('messages.table_order')
                    </p>
                </div>

                <div class="col-4 mb-1">
                    <p class="font-15 font-700 color-theme">
                        @lang('messages.table')
                    </p>
                </div>
                <div class="col-8 mb-1 text-left">
                    <p class="font-14 font-600  color-theme">
                        {{app()->getLocale() == 'ar' ? $table->name_ar: $table->name_en}}
                    </p>
                </div>

            </div>


            <div class="row mb-0 pt-3 mt-5 mr-1 ml-1 py-3" style="border: 1px dashed #f7b538;">
                <div class="col-12 mb-1  mt-n4  text-right ">
                    <span class="bg-white py-4 px-3 font-16 font-700 color-theme">
                        @lang('messages.order_review')
                    </span>
                </div>


                {{--                <div class="col-12 mb-1 mt-2">--}}
                {{--                    <p class="font-15 font-700 color-theme"> اوصال لحم </p>--}}
                {{--                </div>--}}
                {{--                <div class="col-12 mb-1">--}}
                {{--                    <p class="font-14 color-gray2-dark"> وصف مختصر للوجبة هنا</p>--}}
                {{--                </div>--}}
                {{--                <div class="col-12 mb-1">--}}
                {{--                    <p class="font-15 font-700 color-theme">الاضافات </p>--}}
                {{--                </div>--}}
                {{--                <div class="col-12 mb-1">--}}
                {{--                    <p class="font-14 color-gray2-dark"> وصف مختصر للوجبة هنا</p>--}}
                {{--                </div>--}}
                @foreach($items as $item)
                    <div class="col-12 mb-1 mt-2">
                        <p class="font-15 font-600 color-theme">
                            {{app()->getLocale() == 'ar' ? $item->product->name_ar : $item->product->name_en}}
                            x {{$item->product_count}}
                        </p>
                        <h3 class="font-11 float-left mr-2 mt-2">
                            {{$item->price}}
                            {{app()->getLocale() == 'ar' ? $item->product->branch->country->currency_ar : $item->product->branch->country->currency_en}}
                        </h3>
                    </div>
                    <div class="col-12 mb-1">
                        <p class="font-14 color-gray2-dark">
                            {{app()->getLocale() == 'ar' ? $item->product->description_ar : $item->product->description_en}}
                        </p>
                    </div>

                    @if($item->order_item_options->count() > 0)
                        <div class="col-12 mb-1">
                            <p class="font-15 font-600 color-theme">@lang('messages.additions') </p>
                        </div>
                        @foreach($item->order_item_options as $option)
                            <div class="col-12 mb-1">
                                <label for="size3-fac-radio" class="color-dark1-dark font-13">
                                        <span class="minw100 font-13 color-gray2-dark">
                                            {{app()->getLocale() == 'ar' ? $option->option->name_ar : $option->option->name_en}}
                                        </span>
                                    x {{$option->option_count}}
                                </label>
                                <h3 class="font-11 float-left mr-2 mt-2">
                                    {{$option->option->price}}
                                    {{app()->getLocale() == 'ar' ? $item->product->branch->country->currency_ar : $item->product->branch->country->currency_en}}
                                </h3>
                            </div>
                        @endforeach
                    @endif
                    <hr>

                @endforeach


            </div>


            <div class="divider mt-4 mb-2"></div>
            <div class="row mb-0 pr-4 pl-4">
                <div class="col-4">
                    <p class="font-15 font-700 color-theme">
                        @lang('messages.order_value')
                    </p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-14 font-600  color-theme">
                        {{$order->order_price}}
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>
                </div>
                <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                <div class="col-4">
                    <p class="font-15 font-700 color-theme">@lang('messages.tax')</p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-14 font-600 color-theme">
                        {{$order->tax}}
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>
                </div>
                <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                @if($order->seller_code_id != null || $order->discount_id)
                    <div class="col-4">
                        <p class="font-15 font-700 color-theme"> @lang('messages.discount') </p>
                    </div>
                    <div class="col-8 text-left">
                        <p class="font-14 font-600  color-theme">
                            {{$order->discount_value}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        </p>
                    </div>
                @endif
                <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                <div class="col-4">
                    <p class="font-15 font-700 color-theme">
                        @lang('messages.total')
                    </p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-14 font-600 color-theme">
                        @if($order->delivery_value != null)
                            {{$order->total_price + $order->delivery_value}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        @else
                            @if($order->discount_id)
                                {{$order->total_price - $order->discount_value}}
                            @else
                                {{$order->total_price}}
                            @endif
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        @endif
                    </p>
                </div>

            </div>
            <div class="text-center">
                @if($branch->main == 'true')
                    <a href="{{route('sliverHomeTable' , [$restaurant->name_barcode , $table->name_barcode])}}"
                       class="btn btn-l bg-highlight rounded-sm shadow-xl text-uppercase font-900">
                        @lang('messages.finish_order')
                    </a>

                @else
                    <a href="{{route('sliverHomeTableBranch' , [$restaurant->name_barcode , $table->name_barcode , $branch->name_barcode])}}"
                       class="btn btn-l bg-highlight rounded-sm shadow-xl text-uppercase font-900">
                        @lang('messages.finish_order')
                    </a>
                @endif
            </div>


        </div>


    </div>


</div>


@include('website.'.session('theme_path').'silver.layout.scripts')
