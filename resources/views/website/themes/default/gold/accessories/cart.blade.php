@include('website.'.session('theme_path').'silver.layout.header')
<?php  $order_price = 0; ?>

<?php $delivery_value = null; ?>

<div class="card header-card shape-rounded" style="min-height:100px;" data-card-height="100">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg"></div>
</div>
<div class="card cart-content mr-0 ml-0 rounded-l" style="margin-top:40px;">


    <h4 class="mb-1 mt-0 text-center"
        style="z-index: 99; color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> {{ trans('messages.review_order') }}</h4>

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
        @if($items->count() > 0)
            <div class="row mb-0 pt-3 mt-5 mr-1 ml-1 py-3" style="border: 1px dashed #f7b538;">
                <div class="col-12 mb-1  mt-2  text-right ">
                    <span class="bg-white py-4 font-16 font-900 color-theme"> {{ trans('messages.order_summery') }}
                        {{--                        <span class="float-left ml-3">--}}
                        {{--                            <a href="#" class="font-13 color-black">تعديل</a>--}}
                        {{--                        </span>--}}
                    </span>
                </div>
                @foreach($items as $item)
                    <div class="order-d col-12">
                        <div class="row">
                            <div class="col-12 mb-1 mt-2">
                                <span class="font-15 float-right font-600 color-theme">
                                    {{app()->getLocale() == 'ar' ? $item->product->name_ar : $item->product->name_en}}
                                    x {{$item->product_count}}
                                </span>
                                <h3 class="font-11 float-left mr-2 mt-2">
                                    {{$item->price * $item->product_count}}
                                    {{app()->getLocale() == 'ar' ? $item->product->branch->country->currency_ar : $item->product->branch->country->currency_en}}
                                </h3>
                                <br>
                                {{-- <p class="ml-3 widget-title-color-red">
                                    <a href="{{route('deleteOrderItem' , $item->id)}}" class="font-13" style="color: red">
                                        @lang('messages.delete')
                                    </a>
                                </p> --}}
                            </div>
                            <div class="col-12 mb-1">
                                <p class="font-14 color-gray2-dark"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}!important">
                                    {!! app()->getLocale() == 'ar' ? $item->product->description_ar : $item->product->description_en !!}
                                </p>
                            </div>
                            <?php  $order_price += $item->price * $item->product_count; ?>
                            @if($item->order_item_options->count() > 0)
                                <div class="col-12 mb-1">
                                    <p class="font-15 font-600 color-theme">@lang('messages.additions') </p>
                                </div>
                                @foreach($item->order_item_options as $option)
                                    <div class="col-12 mb-1">
                                        <label for="size3-fac-radio" class="color-dark1-dark font-13">
                                                <span class="minw100 font-13 color-gray2-dark"
                                                      style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}!important">
                                                    {{app()->getLocale() == 'ar' ? $option->option->name_ar : $option->option->name_en}}
                                                </span>
                                            x {{$option->option_count * $item->product_count}}
                                        </label>
                                        <h3 class="font-11 float-left mr-2 mt-2">
                                            {{$option->option->price * $option->option_count * $item->product_count}} 
                                            {{app()->getLocale() == 'ar' ? $item->product->branch->country->currency_ar : $item->product->branch->country->currency_en}}
                                        </h3>
                                    </div>
                                    <?php  $order_price += $option->option->price * $option->option_count; ?>
                                @endforeach
                            @endif
                        </div>
                        <a class="delete-order" href="{{route('deleteOrderItem' , $item->id)}}">
                            <span><i class="far fa-trash-alt"></i></span>
                        </a>
                    </div>
                    <hr>

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
                        {{$order->order_price}}
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>
                </div>
                <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                @if($branch->tax == 'true' and $branch->tax_value > 0)
                    <div class="col-4">
                        <p class="font-14 font-700 color-theme">@lang('messages.tax')</p>
                    </div>
                    <div class="col-8 text-left">
                        <p class="font-13 font-600 color-theme">
                            {{$branch->tax_value * $order_price / 100}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        </p>
                    </div>
                    <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                @endif
                @if($order->seller_code_id != null)
                    <div class="col-4">
                        <p class="font-14 font-700 color-theme"
                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }} !important;">@lang('messages.discount')</p>
                    </div>
                    <div class="col-8 text-left"
                         style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }} !important;">
                        <p class="font-13 font-600 color-theme">
                            {{$order->discount_value}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        </p>
                    </div>
                @endif
                <div class="col-4" id="delivery_value_text" style="display: none">
                    <p class="font-14 font-700 color-theme">@lang('messages.delivery_value')</p>
                </div>
                <div class="col-8 text-left " id="delivery_value" style="display: none">
                    <p class="font-13 font-600 color-theme" id="delivery_inner_value">
                        @php
                            $delivery_value = \App\Models\RestaurantOrderSetting::whereRestaurantId($restaurant->id)
                                    ->where('branch_id', $branch->id)
                                    ->where('order_type', 'delivery')
                                    ->first();
                        @endphp
                        @if($delivery_value != null)
                            {{$delivery_value->delivery_value}}
                            {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                        @endif

                    </p>
                </div>
                <div class="col-4">
                    <p class="font-14 font-700 color-theme">@lang('messages.total')</p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-13 font-600 color-theme" id="total_price" data-type=""
                       data-order_price="{{$order->order_price}}"
                       data-tax_price="{{($branch->tax == 'true' and $branch->tax_value > 0) ? (($branch->tax_value * $order_price) / 100) : 0}}">
                        @if($branch->tax == 'true' and $branch->tax_value > 0)
                            {{$order->order_price + (($branch->tax_value * $order_price) / 100)}}
                        @else
                            {{$order->total_price}}
                        @endif
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>

                </div>
            </div>
            <div class="divider mt-5 mb-2"></div>
            @php
                $seller_code = \App\Models\RestaurantOrderSellerCode::where('restaurant_id', $restaurant->id)->first();
                $setting_whatsapp_order = \App\Models\RestaurantOrderSetting::whereRestaurantId($restaurant->id)
                    ->where('branch_id', $branch->id)
                    ->where('order_type', 'whatsapp')
                    ->first();
                $setting_easymenu_order = \App\Models\RestaurantOrderSetting::whereRestaurantId($restaurant->id)
                    ->where('branch_id', $branch->id)
                    ->where('order_type', 'easymenu')
                    ->first();
            @endphp

            @if($seller_code and ($setting_whatsapp_order or $setting_easymenu_order))
                <form action="{{route('applyOrderSellerCode' , $order->id)}}" method="post">
                    @csrf
                    <div class="row mb-0 pr-4 pl-4">

                        <div class="col-7 input-style input-style-2">
                            <input type="text" name="seller_code" class="form-control"
                                   placeholder="@lang('messages.seller_code')"/>
                            @if ($errors->has('seller_code'))
                                <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('seller_code') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-5 text-left">
                            <input type="submit"
                                   class="btn btn-l bg-highlight rounded-sm shadow-xl text-uppercase font-900"
                                   style="width:100%; background-color: {{$restaurant->color == null ? '#f7b538' : $restaurant->color->icons}}!important;"
                                   value="@lang('messages.confirm')"/>
                        </div>
                    </div>
                </form>
            @endif

            {{-- <a href="{{route('GoldEmptyCart' , $order->id)}}"
               style="width: 30.8%; background-color: red;padding: 14px !important;"
               class="btn btn-l text-center  color-white rounded-sm shadow-xl mx-2 text-uppercase font-900">
               <i class="far fa-trash-alt"></i> @lang('messages.emptyCart')
            </a> --}}
            @if(($setting_whatsapp_order and checkOrderService($restaurant->id , 9 , $branch->id)) or ($setting_easymenu_order and checkOrderService($restaurant->id , 10 , $branch->id)))
                <form action="{{route('GoldCompleteOrder')}}" method="post">
                    @csrf
                    <input type="hidden" name="order_id" value="{{$order->id}}">
                    <input type="hidden" id="lat" name="latitude" value="26.220503444920748" readonly="yes" required>
                    <input type="hidden" id="lng" name="longitude" value="45.66631930211639" readonly="yes" required>


                    <h4 class="mb-1 font-14"
                        style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important"> @lang('messages.notes')</h4>
                    <div class="input-style input-style-2">
                    <textarea name="notes" class="form-control"
                              placeholder="@lang('messages.write_notes')"></textarea>
                    </div>
                    <div class="row mb-0 pt-3 mr-1 ml-1 py-3">
                        <div class="col-12 mb-1  mt-2  text-right ">
                    <span class="bg-white py-4 font-16 font-900 color-theme">
                        @lang('messages.receipt_method')
                    </span>

                            <div class="content mb-0">


                                @if($setting_whatsapp_order != null and checkOrderService($restaurant->id , 9) == true)
                                    <script>
                                        var whatsappDeliveryValue = {{$setting_whatsapp_order->delivery == 'true' ? $setting_whatsapp_order->delivery_value : 0}};
                                    </script>
                                    <div class="fac fac-radio fac-orange mb-1 mr-4">
                                        <label for="box11-fac-radio" class="color-dark1-dark ">
                                            <input id="box11-fac-radio" type="radio" name="order_type" value="whatsapp" checked
                                                   data-delivery="{{$setting_whatsapp_order->delivery}}"
                                                   data-delivery_value="{{$setting_whatsapp_order->delivery_value}}">
                                            <span class="checkmark"></span>
                                            <span class="minw100 color-gray2-dark font-15 font-600"
                                                  style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}!important">
                                                @lang('dashboard.whatsapp_orders')
                                            </span>
                                        </label>
                                    </div>
                                @endif

                                @if($setting_easymenu_order != null and checkOrderService($restaurant->id , 10) == true)
                                    <script>
                                        var easyMenuDeliveryValue = {{$setting_easymenu_order->delivery == 'true' ? $setting_easymenu_order->delivery_value : 0}};
                                    </script>
                                    <div class="fac fac-radio fac-orange mb-1 mr-4">
                                        <label for="box11-fac-radio" class="color-dark1-dark ">
                                            <input id="box11-fac-radio" type="radio" name="order_type" value="easymenu" checked
                                                   data-delivery="{{$setting_easymenu_order->delivery}}"
                                                   data-delivery_value="{{$setting_easymenu_order->delivery_value}}">
                                            <span class="checkmark"></span>
                                            <span class="minw100 color-gray2-dark font-15 font-600"
                                                  style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}!important">
                                                {{app()->getLocale() == 'ar' ? 'كاشير ايزي منيو':'Easy Menu Casher'}}
                                            </span>
                                        </label>
                                    </div>
                                @endif

                            </div>

                            <div class="content">
                                <div class="whatsapp branche"
                                     style="display: {{($setting_whatsapp_order and $setting_easymenu_order == null) ? 'block' : 'none'}}">
                                    <div class="input-style input-style-2">
                                        <h3> @lang('messages.branch')
                                            : {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}} </h3>
                                    </div>
                                    @if($setting_whatsapp_order)
                                        @php
                                            $carbon = \Carbon\Carbon::now();
                                            $current_day = $carbon->format('l');
                                            $day = \App\Models\Day::where('name_en', $current_day)->first();
                                            $setting_whatsapp_period = \App\Models\RestaurantOrderPeriod::with('days')
                                                ->whereHas('days', function ($q) use ($day) {
                                                    $q->where('day_id', $day->id);
                                                })
                                                ->whereRestaurantId($restaurant->id)
                                                ->where('type', 'previous')
                                                ->where('branch_id', $branch->id)
                                                ->whereSettingId($setting_whatsapp_order->id)
                                                ->first();
                                        @endphp
                                    @endif
                                    <div class="input-style input-style-2">
                                        <select name="previous_type" id="receipt_methodW"
                                                onchange="showDivWhatsappPeriods(this); showPreviousDelivery(this) ">
                                            <option value="default" selected
                                                    disabled> @lang('messages.receipt_method') </option>
                                            @if($setting_whatsapp_order and $setting_whatsapp_order->delivery == 'true')
                                                <option value="delivery"> @lang('messages.delivery') </option>
                                            @endif
                                            @if($setting_whatsapp_order and $setting_whatsapp_order->takeaway == 'true')
                                                <option value="takeaway"> @lang('messages.takeaway') </option>
                                            @endif
                                            @if($setting_whatsapp_order and $setting_whatsapp_order->previous == 'true' and ($setting_whatsapp_period != null and check_time_between($setting_whatsapp_period->start_at , $setting_whatsapp_period->end_at)))
                                                <option value="previous"> @lang('messages.previous') </option>
                                            @endif
                                        </select>
                                        @if ($errors->has('previous_type'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('previous_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @php
                                        $whatsappBranches = App\Models\WhatsappBranch::where('restaurant_id' , $restaurant->id)->get();
                                    @endphp
                                    @if($whatsappBranches->count() > 0)
                                    <div class="input-style input-style-2 whatsapp_branch" id="hidden_previous3"
                                    style="display: block">
                                            <select name="branch_id">
                                                <option value="" disabled selected>{{ trans('messages.choose_branch') }}</option>
                                                
                                                @foreach ($whatsappBranches as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('branch_id'))
                                                <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                            </span>
                                            @endif
                                    </div>
                                    @endif
                                    <div class="input-style input-style-2 ">
                                        <select name="payment_method" onchange="showDivWhatsapp(this)"
                                                class="chosen-select" required>
                                            <option value="default" disabled=""
                                                    selected=""> @lang('messages.payment_method') </option>
                                        </select>
                                        <p class="text-danger"></p>
                                        @if ($errors->has('payment_method'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @if($setting_whatsapp_order and $setting_whatsapp_order->payment_company == 'myFatoourah')
                                   
                                        <div class="input-style input-style-2 hidden_previous" id="hidden_previous1"
                                             style="display: none;">
                                            <select name="payment_type">
                                                <option value="default" disabled=""
                                                        selected=""> @lang('messages.payment_type') </option>
                                                <option value="visa">@lang('messages.visa')</option>
                                                <option value="mada"> @lang('messages.mada') </option>
                                                <option value="apple_pay"> @lang('messages.apple_pay') </option>
                                            </select>
                                            @if ($errors->has('payment_method'))
                                                <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($setting_whatsapp_order)
                                        <div style="display: none" id="whatsAppPeriods">
                                            <div class="input-style input-style-2">
                                                <select name="previous_type_method"
                                                        onchange="showPreviousDelivery(this)">
                                                    <option value="default" disabled=""
                                                            selected=""> @lang('messages.order_type') </option>
                                                    @if($setting_whatsapp_order and $setting_whatsapp_order->delivery == 'true')
                                                        <option value="delivery"> @lang('messages.delivery') </option>
                                                    @endif
                                                    @if($setting_whatsapp_order and $setting_whatsapp_order->takeaway == 'true')
                                                        <option value="takeaway"> @lang('messages.takeaway') </option>
                                                    @endif
                                                </select>
                                                @if ($errors->has('previous_type'))
                                                    <span class="help-block">
                                                    <strong
                                                        style="color: red;">{{ $errors->first('previous_type') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <?php
                                            $periods = \App\Models\RestaurantOrderPeriod::whereSettingId($setting_whatsapp_order->id)
                                                ->where('type', 'setting')
                                                ->where('setting_id', '!=', null)
                                                ->get();
                                            ?>
                                            <div class="input-style input-style-2">
                                                <select name="period_id">
                                                    <option value="default" disabled=""
                                                            selected=""> @lang('messages.receipt_period') </option>
                                                    @foreach($periods as $period)
                                                        @if($period->days->count() > 0)
                                                            <option value="{{$period->id}}">
                                                                @lang('messages.start_at') : {{$period->start_at}}
                                                                @lang('messages.end_at') : {{$period->end_at}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-style input-style-2">
                                                <select id="day_id" name="day_id" class="form-control" required>
                                                    <option disabled
                                                            selected> @lang('messages.choose_receipt_day') </option>

                                                </select>
                                                @if ($errors->has('day_id'))
                                                    <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('day_id') }}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                <div class="easymenu branche"
                                     style="display: {{($setting_easymenu_order and $setting_whatsapp_order == null) ? 'block' : 'none'}};">
                                    <div class="input-style input-style-2">
                                        <h3> @lang('messages.branch')
                                            : {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}} </h3>
                                    </div>
                                    @if($setting_easymenu_order)
                                        @php
                                            $carbon = \Carbon\Carbon::now();
                                            $current_day = $carbon->format('l');
                                            $day = \App\Models\Day::where('name_en', $current_day)->first();
                                            $setting_easyMenu_period = \App\Models\RestaurantOrderPeriod::with('days')
                                                ->whereHas('days', function ($q) use ($day) {
                                                    $q->where('day_id', $day->id);
                                                })
                                                ->whereRestaurantId($restaurant->id)
                                                ->where('type', 'previous')
                                                ->where('branch_id', $branch->id)
                                                ->whereSettingId($setting_easymenu_order->id)
                                                ->first();
                                        @endphp
                                    @endif
                                    <div class="input-style input-style-2">
                                        <select name="previous_type" id="receipt_method"
                                                onchange="showDivEasyMenuPeriods(this); showPreviousDelivery(this)">
                                            <option value="default" disabled
                                                    selected> @lang('messages.receipt_method') </option>
                                            @if($setting_easymenu_order and $setting_easymenu_order->delivery == 'true')
                                                <option value="delivery"> @lang('messages.delivery') </option>
                                            @endif
                                            @if($setting_easymenu_order and $setting_easymenu_order->takeaway == 'true')
                                                <option value="takeaway"> @lang('messages.takeaway') </option>
                                            @endif
                                            @if($setting_easymenu_order and $setting_easymenu_order->previous == 'true' and (($setting_easyMenu_period != null and check_time_between($setting_easyMenu_period->start_at , $setting_easyMenu_period->end_at)) or $setting_easyMenu_period == null))
                                                <option value="previous"> @lang('messages.previous') </option>
                                            @endif
                                        </select>
                                        @if ($errors->has('previous_type'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('previous_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="input-style input-style-2 whatsapp_branch" id="hidden_previous3"
                                    style="display: block">
                                            <select name="branch_id">
                                                <option value="" disabled selected>{{ trans('messages.branch') }}</option>
                                                @php
                                                    $branches = App\Models\WhatsappBranch::where('restaurant_id' , $restaurant->id)->get();
                                                @endphp
                                                @foreach ($branches as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('branch_id'))
                                                <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                            </span>
                                            @endif
                                    </div>
                                    <div class="input-style input-style-2 ">
                                        <select name="payment_method" onchange="showDivEasyMenu(this)" required
                                                class="chosen-select">
                                            <option value="default" disabled=""
                                                    selected=""> @lang('messages.payment_method') </option>

                                        </select>
                                        <p class="text-danger"></p>
                                        @if ($errors->has('payment_method'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @if($setting_easymenu_order and $setting_easymenu_order->payment_company == 'myFatoourah')
                                  
                                        <div class="input-style input-style-2 hidden_previous" id="hidden_previous2"
                                             style="display: none">
                                            <select name="payment_type">
                                                <option value="default" disabled=""
                                                        selected=""> @lang('messages.payment_type') </option>
                                                <option value="visa">@lang('messages.visa')</option>
                                                <option value="mada"> @lang('messages.mada') </option>
                                                <option value="apple_pay"> @lang('messages.apple_pay') </option>
                                            </select>
                                            @if ($errors->has('payment_method'))
                                                <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    @endif

                                    @if($setting_easymenu_order)
                                        <div style="display: none" id="easyMenuPeriods">
                                            <div class="input-style input-style-2">
                                                <select name="previous_type_method"
                                                        onchange="showPreviousDelivery(this)">
                                                    <option value="default" disabled=""
                                                            selected=""> @lang('messages.order_type') </option>
                                                    @if($setting_easymenu_order and $setting_easymenu_order->previous_order_type == 'delivery')
                                                        <option value="delivery"> @lang('messages.delivery') </option>
                                                    @endif
                                                    @if($setting_easymenu_order and $setting_easymenu_order->previous_order_type == 'takeaway')
                                                        <option value="takeaway"> @lang('messages.takeaway') </option>
                                                    @endif
                                                    @if($setting_easymenu_order and $setting_easymenu_order->previous_order_type == 'both')
                                                        <option value="delivery"> @lang('messages.delivery') </option>
                                                        <option value="takeaway"> @lang('messages.takeaway') </option>
                                                    @endif
                                                </select>
                                                @if ($errors->has('previous_type'))
                                                    <span class="help-block">
                                                    <strong
                                                        style="color: red;">{{ $errors->first('previous_type') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <?php
                                            $periods = \App\Models\RestaurantOrderPeriod::whereSettingId($setting_easymenu_order->id)
                                                ->where('type', 'setting')
                                                ->where('setting_id', '!=', null)
                                                ->get();
                                            ?>
                                            <div class="input-style input-style-2">
                                                <select name="period_id">
                                                    <option value="default" disabled=""
                                                            selected=""> @lang('messages.receipt_period') </option>
                                                    @foreach($periods as $period)
                                                        @if($period->days->count() > 0)
                                                            <option value="{{$period->id}}">
                                                                @lang('messages.start_at') : {{$period->start_at}}
                                                                @lang('messages.end_at') : {{$period->end_at}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-style input-style-2">
                                                <select id="day_id" name="day_id" class="form-control" required>
                                                    <option disabled
                                                            selected> @lang('messages.choose_receipt_day') </option>

                                                </select>
                                                @if ($errors->has('day_id'))
                                                    <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('day_id') }}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row mb-0 mr-1 ml-1" id="btn-save-order">
                        <div class="col-12">
                            <div class="content mt-0 pt-1">
                                <div class="text-center mt-3 mb-3" id="position_id" style="display: none">
                                    <a onclick="getLocation()" id="locat"
                                       style="display: none; width: 100%; color:#fff; background-color:{{$restaurant->color == null ? '#f7b538' : $restaurant->color->icons}}!important "
                                       class="btn btn-l  rounded-sm shadow-xl text-uppercase font-900">
                                        <i class="fas fa-map-marker-alt"></i> @lang('messages.MyPosition')
                                    </a>
                                </div>
                                <div id="showPositionResult" class="text-center"></div>

                                <div class="text-center mt-3">
                                    @if($branch->state == 'closed')
                                        {{--                                    <button type="submit" id="dependOrder" disabled--}}
                                        {{--                                            style="    width: 46.8%; color:#fff; background-color:#f7b538!important "--}}
                                        {{--                                            class="btn btn-l  rounded-sm shadow-xl text-uppercase font-900">--}}
                                        {{--                                        @lang('messages.depend_order')--}}
                                        {{--                                    </button>--}}
                                        <h5 class="text-left" style="color: red"> المطعم مغلق </h5>
                                    @elseif($branch->state == 'busy')
                                        {{--                                    <button type="submit" id="dependOrder" disabled--}}
                                        {{--                                            style="    width: 46.8%; color:#fff; background-color:#f7b538!important "--}}
                                        {{--                                            class="btn btn-l  rounded-sm shadow-xl text-uppercase font-900">--}}
                                        {{--                                        @lang('messages.depend_order')--}}
                                        {{--                                    </button>--}}
                                        <h5 class="text-left" style="color: red"> المطعم مشغول </h5>
                                    @else
                                        @if(check_branch_periods($branch->id))
                                            <button type="submit" id="dependOrder" disabled
                                                    style="display: none;text-align: center!important; width: 100%; color:#fff; background-color:{{$restaurant->color == null ? '#f7b538' : $restaurant->color->icons}}!important "
                                                    class="btn btn-l text-center  rounded-sm shadow-xl text-uppercase font-900">
                                                @lang('messages.depend_order')
                                            </button>
                                        @else
                                            {{--                                        <button type="submit" id="dependOrder" disabled--}}
                                            {{--                                                style="    width: 46.8%; color:#fff; background-color:#f7b538!important "--}}
                                            {{--                                                class="btn btn-l  rounded-sm shadow-xl text-uppercase font-900">--}}
                                            {{--                                            @lang('messages.depend_order')--}}
                                            {{--                                        </button>--}}
                                            <h5 class="text-left" style="color: red"> الطلبات غير متاحة في الوقت
                                                الحالي </h5>
                                        @endif
                                    @endif

                                </div>
                            </div>

                        </div>
                </form>
            @endif
        @endif
    </div>

</div>
@if($items->count() == 0)
    <!-- <div class="card mr-0 ml-0 rounded-l">
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
    <h2 class="text-center"
        style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }} !important;"> @lang('messages.no_orders') </h2>
    <br>
    <br>
    <br>
    </div>
@endif

<div id="menu-map" class="menu menu-box-modal rounded-m"
     data-menu-height="350"
     data-menu-width="400">
    <div>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14932236.388441794!2d54.10903034479226!3d23.97579920371529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15e7b33fe7952a41%3A0x5960504bc21ab69b!2z2KfZhNiz2LnZiNiv2YrYqQ!5e0!3m2!1sar!2s!4v1661837796444!5m2!1sar!2s"
            width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

</div>
@if($order != null)
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>

        $(document).ready(function () {
            $('input[type="radio"]').click(function () {
                var tag = $(this);
                var inputValue = $(this).attr("value");
                var targetBox = $("." + inputValue);
                $(".branche").not(targetBox).hide();
                $(targetBox).show();
                // console.log(targetBox, inputValue);
                // console.log('check');
                document.getElementById('position_id').style.display = 'block';
                var dv = {{$delivery_value == null ? 0 : $delivery_value->delivery_value}};
                $('.whatsapp_branch').hide(1);
                if (inputValue == 'whatsapp') {
                    dv = whatsappDeliveryValue;
                    $('.whatsapp_branch').fadeIn(300);
                } else if (inputValue == 'previous') {
                    dv = previousDeliveryValue;
                } else if (inputValue == 'easymenu') {
                    dv = easyMenuDeliveryValue;
                }
                var orderTotalPrice = {{$order->total_price}};
                var currency = "{{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}";
                console.log(currency);
                var tagTotalPrice = $('#total_price');
                tagTotalPrice.data('type', inputValue);
                if (inputValue == 'delivery' && dv != 0) {
                    document.getElementById('delivery_value_text').style.display = 'block';
                    document.getElementById('delivery_value').style.display = 'block';
                    document.getElementById('total_price').textContent = parseFloat(document.getElementById('total_price').innerText) + dv + ' ' + currency;
                    document.getElementById('delivery_inner_value').textContent = dv + ' ' + currency;

                } else {
                    var dv = 0;
                    if (tag.data('delivery')) {
                        // dv = tag.data('delivery_value');


                    }
                    console.log(tag.data());
                    console.log('inside');
                    console.log(tagTotalPrice.data());
                    document.getElementById('delivery_value_text').style.display = 'none';
                    document.getElementById('delivery_value').style.display = 'none';
                    document.getElementById('total_price').textContent = parseFloat(tagTotalPrice.data('order_price')) + parseFloat(tagTotalPrice.data('tax_price')) + parseFloat(dv) + ' ' + currency;


                }
            });
            $('input[type="radio"]').trigger('click');
            $(".chosen-select").change(function () {
                $("#dependOrder").prop('disabled', false);
                document.getElementById('position_id').style.display = 'block';
                document.getElementById('locat').style.display = 'block';
            });
        });
    </script>
    <script>
        function showDiv(element) {
            if (element.value == 'online_payment') {
                document.getElementById('hidden_div').style.display = element.value == 'online_payment' ? 'block' : 'none';
            } else {
                document.getElementById('hidden_div').style.display = 'none';
                document.getElementById('hidden_takeaway').style.display = 'none';
                document.getElementById('hidden_previous').style.display = 'none';
            }
        }

        function showDivTake(element) {
            if (element.value == 'online_payment') {
                document.getElementById('hidden_takeaway').style.display = 'block';
            } else {
                document.getElementById('hidden_takeaway').style.display = 'none';
                document.getElementById('hidden_div').style.display = 'none';
                document.getElementById('hidden_previous').style.display = 'none';
            }
        }

        function showPreviousDelivery(element) {
            var dpv = {{$delivery_value == null ? 0 : $delivery_value->delivery_value}};

            var previousType = $('input[name=order_type]:checked');
            var totalPrice = $('#total_price');
            dpv = previousType.data('delivery_value');
            var currency = "{{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}";
            if (element.value == 'delivery') {
                document.getElementById('delivery_value_text').style.display = 'block';
                document.getElementById('delivery_value').style.display = 'block';
                document.getElementById('total_price').textContent = parseFloat(document.getElementById('total_price').innerText) + dpv + ' ' + currency;
                document.getElementById('delivery_inner_value').textContent = dpv + ' ' + currency;

                var total_price = parseFloat(totalPrice.data('order_price')) + parseFloat(totalPrice.data('tax_price')) + parseFloat(dpv);

                totalPrice.text(total_price.toFixed(1) + ' ' + currency);
            } else {
                if (true) {
                    // document.getElementById('total_price').textContent = parseFloat(document.getElementById('total_price').innerText) - dpv + ' ' + currency;
                    var total_price = parseFloat(totalPrice.data('order_price')) + parseFloat(totalPrice.data('tax_price'));

                    totalPrice.text(total_price.toFixed(1) + ' ' + currency);
                }
                document.getElementById('delivery_value_text').style.display = 'none';
                document.getElementById('delivery_value').style.display = 'none';
            }
        }

        function showDivPrevious(element) {
            if (element.value == 'online_payment') {
                document.getElementById('hidden_previous').style.display = element.value == 'online_payment' ? 'block' : 'none';
            } else {
                document.getElementById('hidden_previous').style.display = 'none';
                document.getElementById('hidden_takeaway').style.display = 'none';
                document.getElementById('hidden_div').style.display = 'none';
            }
        }

        function showDivWhatsapp(element) {
            if (element.value == 'online_payment') {

                document.getElementById('hidden_previous1').style.display = element.value == 'online_payment' ? 'block' : 'none';
            } else {
                document.getElementById('hidden_previous1').style.display = 'none';
                document.getElementById('hidden_takeaway1').style.display = 'none';
                document.getElementById('hidden_div1').style.display = 'none';
            }
        }

        function showDivEasyMenu(element) {
            if (element.value == 'online_payment') {
                document.getElementById('hidden_previous2').style.display = element.value == 'online_payment' ? 'block' : 'none';
            } else {
                document.getElementById('hidden_previous2').style.display = 'none';
                document.getElementById('hidden_takeaway1').style.display = 'none';
                document.getElementById('hidden_div1').style.display = 'none';
            }
        }

        function showDivWhatsappPeriods(element) {
            if (element.value == 'previous') {
                document.getElementById('whatsAppPeriods').style.display = 'block';
            } else {
                document.getElementById('whatsAppPeriods').style.display = 'none';
            }
        }

        function showDivEasyMenuPeriods(element) {
            if (element.value == 'previous') {
                document.getElementById('easyMenuPeriods').style.display = 'block';
            } else {
                document.getElementById('easyMenuPeriods').style.display = 'none';
            }
        }
    </script>
    <script>
        var loyaltyBalance = 0;
        var totalPrice = {{$order->total_price}};
        $(document).ready(function () {
      
            $('select[name=payment_method]').on('change' , function(){
                var tag = $(this);
                console.log(tag.val() , loyaltyBalance , totalPrice);
                if(tag.val() == 'loyalty_point'){
                    if(totalPrice > loyaltyBalance){
                        tag.parent().find('.text-danger').text("{{trans('messages.empty_balance')}}");
                        $('#btn-save-order').fadeOut(100);
                        return 0;
                    }
                }
                tag.parent().find('.text-danger').html("");
                $('#btn-save-order').fadeIn(100);
            });
            $('select[name="period_id"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/get/days/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $('#day_id').empty();
                        // $('select[name="city_id"]').append("<option disabled selected> choose </option>");
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $('select[name="day_id"]');
                        $.each(data, function (index, days) {
                            @if(app()->getLocale() == 'ar')
                            $('select[name="day_id"]').append('<option value="' + days.day.id + '">' + days.day.name_ar + '</option>');
                            @else
                            $('select[name="day_id"]').append('<option value="' + days.day.id + '">' + days.day.name_en + '</option>');
                            @endif
                        });
                    }
                });
            });
            $('select[name="previous_type"]').on('change', function () {
                var type = $(this).val();
                $.ajax({
                    url: '/get/{{$order->id}}/payment_types/' + type,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        loyaltyBalance = data.loyaltyBalance;
                        $('select[name="payment_method"]').empty();
                        $('select[name="payment_method"]').append("<option disabled selected> اختر طريقه الدفع </option>");
                        if (data.data == 'online' && data.online == 'true') {
                            $('select[name="payment_method"]').append('<option value="online_payment">الدفع الأونلاين</option>');
                        } else if (data.data == 'receipt' && data.receipt == 'true') {
                            $('select[name="payment_method"]').append('<option value="receipt_payment">الدفع عند الأستلام</option>');
                        } else if (data.data == 'both') {
                            if (data.receipt == 'true') {
                                $('select[name="payment_method"]').append('<option value="receipt_payment">الدفع عند الأستلام</option>');

                            }
                            if (data.online == 'true') {
                                $('select[name="payment_method"]').append('<option value="online_payment">الدفع الأونلاين</option>');
                            }
                        }
                        if(data.loyalty_point == 'true'){
                            $('select[name="payment_method"]').append('<option value="loyalty_point">نقاط الولاء</option>');  
                        }
                        if (data.bank == 'true') {
                            $('select[name="payment_method"]').append('<option value="bank_transfer"> تحويل بنكي</option>');
                        }
                    }
                });
            });
        });
    </script>

    <script>

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success function
                    showPosition,
                    // Error function
                    function (data) {
                        console.error('location error');
                        alert('الرجاء السماح بصلاحيه الوصول الي موقعك الحالي');
                    },
                    // Options. See MDN for details.
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    });
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            document.getElementById('lat').value = position.coords.latitude; //latitude
            document.getElementById('lng').value = position.coords.longitude; //longitude
            document.getElementById("locat").style.backgroundColor = "green";
            document.getElementById("locat").style.border = "green";
            document.getElementById("locat").innerHTML = "{{app()->getLocale() == 'ar' ? 'تم تحديد موقعك':'Your Location is determinted  '}}";
            var inputValue = $('#receipt_method').val();
            if (inputValue == null) {
                inputValue = $('#receipt_methodW').val();
            }
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: '/user/{{$order->id}}/show_position/' + position.coords.latitude + '/' + position.coords.longitude + '/' + inputValue,
                dataType: 'json',
                success: function (data) {
                    if (data.status == false) {
                        document.getElementById("showPositionResult").style.color = 'red';
                        document.getElementById("showPositionResult").innerHTML = data.data;
                        document.getElementById("dependOrder").style.display = 'none';
                    } else {
                        console.log(data.type);
                        document.getElementById("showPositionResult").style.color = 'green';
                        document.getElementById("showPositionResult").innerHTML = data.data;
                        document.getElementById("dependOrder").style.textAlign = 'center!important';
                        document.getElementById("dependOrder").style.display = 'block';

                    }
                }, error: function () {
                    console.log(data);
                }
            });
        }
    </script>
@endif
<style>
    .branche {
        display: none;
    }

    body {
        position: relative;
        overflow-x: hidden
    }

    .header-card {
        z-index: 1;
        top: -40px;
    }

    .order-d {
        margin-bottom: 20px;
    }

    .order-d .delete-order {
        background: red !important;
    }

    @if($restaurant->color)
    .card {
        background-color: {{$restaurant->color == null ? '' : $restaurant->color->background }};
    }

    .bg-white {
        background-color: {{$restaurant->color == null ? '#6a5353' : $restaurant->color->background }}   !important;
    }

    .card-overlay {
        background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background }};
    }

    .theme-light .color-theme {
        color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}  !important;
    }
    @endif

</style>
@include('website.'.session('theme_path').'silver.layout.scripts')
