@include('website.'.session('theme_path').'silver.layout.header')
<?php  $order_price = 0; ?>



<div>
    <div class="card header-card shape-rounded" data-card-height="100">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg"></div>
    </div>


    <div class="card cart-content mr-0 ml-0 rounded-l" style="margin-top:40px;">


        <h4 class="mb-1 mt-0 text-center"
            style="z-index: 99; color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> مراجعة
            الطلب </h3>
            <div class="content mb-3">

                <div class="pb-5">
                    <a href="{{route('sliverHomeTableBranch' , [$restaurant->name_barcode , $table->foodics_id != null ? $table->foodics_id : $table->name_barcode])}}"
                       class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
                       style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                </div>
                @if($table->service_id != null)
                    <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                        {{$table->service->name}}
                    </h3>
                @elseif($table->branch->foodics_status == 'true')
                    <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                        {{\App\Models\Service::find(4)->name}}
                    </h3>
                @endif
                <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.table_orders') </h3>

                @if($table->foodics_branch)
                    <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.branch')
                        : {{app()->getLocale() == 'ar' ? $table->foodics_branch->name_ar : $table->foodics_branch->name_en}} </h3>
                @else
                    <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.branch')
                        : {{app()->getLocale() == 'ar' ? $table->branch->name_ar : $table->branch->name_en}} </h3>
                @endif
                <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.table')
                    : {{app()->getLocale() == 'ar' ? $table->name_ar : $table->name_en}} </h3>
                @include('flash::message')
                @if($items->count() > 0)
                    <div class="row mb-0 pt-3 mt-5 mr-1 ml-1 py-3" style="border: 1px dashed #f7b538;">
                        <div class="col-12 mb-1  mt-2  text-right ">
                    <span class="bg-white py-4 font-16 font-900 color-theme"> ملخص الطلب
{{--                        <span class="float-left ml-3">--}}
                        {{--                            <a href="#" class="font-13 color-black">حذف</a>--}}
                        {{--                        </span>--}}
                        {{--                        <span class="float-left ml-3">--}}
                        {{--                            <a href="#" class="font-13 color-black">تعديل</a>--}}
                        {{--                        </span>--}}
                    </span>
                        </div>

                        @foreach($items as $item)
                            <div class="order-d col-12">
                                <div class="row">
                                    <div class="col-12 mb-1 mt-2">
                                        <p class="font-15 font-600 color-theme">
                                            {{app()->getLocale() == 'ar' ? $item->product->name_ar : $item->product->name_en}}
                                            x {{$item->product_count}}
                                        </p>
                                        <span class="font-11 text-center mr-2 mt-2"
                                              style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                                            {{$item->price}}
                                            {{app()->getLocale() == 'ar' ? $item->product->branch->country->currency_ar : $item->product->branch->country->currency_en}}
                                        </span>

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
                                                    x {{$option->option_count}}
                                                </label>
                                                <h3 class="font-11 float-left mr-2 mt-2"
                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                                                    {{$option->option->price}}
                                                    {{app()->getLocale() == 'ar' ? $item->product->branch->country->currency_ar : $item->product->branch->country->currency_en}}
                                                </h3>
                                            </div>
                                            <?php  $order_price += $option->option->price * $option->option_count; ?>
                                        @endforeach
                                    @endif
                                </div>
                                <a class="delete-order" href="{{route('removeTableOrderItem' , $item->id)}}">
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
                        @if($branch->tax == 'true')
                            @php
                                $tax = ($branch->tax_value * $order_price) / 100;
                            @endphp
                            @if($tax > 0)
                                <div class="col-4">
                                    <p class="font-14 font-700 color-theme">@lang('messages.tax')</p>
                                </div>
                                <div class="col-8 text-left">
                                    <p class="font-13 font-600 color-theme">
                                        {{$tax}}
                                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                                    </p>
                                </div>
                                <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                            @endif

                        @endif
                        @if($order->seller_code_id != null)
                            <div class="col-4">
                                <p class="font-14 font-700 color-theme"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">@lang('messages.discount')</p>
                            </div>
                            <div class="col-8 text-left">
                                <p class="font-13 font-600 color-theme"
                                   style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                                    {{$order->discount_value}}
                                    {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                                </p>
                            </div>
                        @endif

                        <div class="col-4"
                             style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                            <p class="font-14 font-700 color-theme">@lang('messages.total')</p>
                        </div>
                        <div class="col-8 text-left">
                            <p class="font-13 font-600 color-theme" id="total_price"
                               style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}">
                                @if($branch->tax == 'true')
                                    {{$order->order_price + (($branch->tax_value * $order_price) / 100)}}
                                @else
                                    {{$order->total_price}}
                                @endif
                                {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                            </p>

                        </div>
                    </div>
                    <div class="divider mt-5 mb-2"></div>
                    <?php
                    $seller_code = \App\Models\RestaurantOrderSellerCode::where('restaurant_id', $order->restaurant_id)
                        ->first();
                    ?>
                    @if($order->branch->foodics_status == 'false' and $seller_code)
                        <form action="{{route('applyTableOrderSellerCode' , $order->id)}}" method="post">
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
                                           style="width:100%" value="@lang('messages.confirm')"/>
                                </div>
                            </div>
                        </form>
                    @endif

                    <form action="{{route('TableCompleteOrder')}}" method="post">
                        @csrf
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <input type="hidden" id="lat" name="latitude" value="" readonly="yes" required>
                        <input type="hidden" id="lng" name="longitude" value="" readonly="yes" required>
                        @if($table->code != null)
                            <div class="col-12 input-style input-style-2">
                                <input type="text" name="table_code" class="form-control" required
                                       placeholder="@lang('messages.table_code')"/>
                                @if ($errors->has('table_code'))
                                    <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('table_code') }}</strong>
                            </span>
                                @endif
                            </div>
                        @endif
                        @if($order->branch->foodics_status == 'true')
                            <div class="form-group">
                                <label class="control-label"
                                       style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.foodics_discount') </label>
                                <input type="text" name="discount_name" value="{{old('discount_name')}}"
                                       class="form-control"
                                       placeholder="{{app()->getLocale() == 'ar' ? 'أذا كان لديك كود خصم اكتبه هنا' : 'Put Your  Seller Code Here'}}">
                                @if ($errors->has('discount_name'))
                                    <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('discount_name') }}</strong>
                            </span>
                                @endif
                            </div>
                        @endif
                        @php
                            if ($table->service)
                            {
                                $setting_table_order = \App\Models\RestaurantOrderSetting::whereRestaurantId($restaurant->id)
                                ->where('branch_id', $branch->id)
                                ->where('order_type', $table->service->type)
                                ->where('table' , 'true')
                                ->where('table_payment' ,'!=' , null)
                                ->first();
                            }else{
                               $setting_table_order = null;
                            }

                        @endphp
                        @if($setting_table_order)
                            <div class="input-style input-style-2 l ">
                                <label class="control-label"
                                       style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.choose') @lang('messages.payment_method')</label>
                                <select name="payment_method" class="chosen-select " onchange="showTablePaymentFatoourah(this)" required>
                                    <option value="default" disabled=""
                                            selected="" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}!important;"> @lang('messages.payment_method') </option>
                                    @if($setting_table_order->table_payment == 'both')
                                        @if($setting_table_order->receipt_payment == 'true')
                                            <option value="receipt_payment"> @lang('messages.receipt_payment') </option>
                                        @endif
                                        @if($setting_table_order->online_payment == 'true')
                                            <option value="online_payment"> @lang('messages.online_payment') </option>
                                        @endif
                                    @elseif($setting_table_order->receipt_payment == 'true' and $setting_table_order->table_payment == 'receipt')
                                        <option value="receipt_payment"> @lang('messages.receipt_payment') </option>
                                    @elseif($setting_table_order->online_payment == 'true' and $setting_table_order->table_payment == 'online')
                                        <option value="online_payment"> @lang('messages.online_payment') </option>
                                    @endif
                                </select>
                                @if ($errors->has('payment_method'))
                                    <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                @endif
                            </div>
                        @endif
                        @if($setting_table_order and $setting_table_order->payment_company == 'myFatoourah')
                            <div class="input-style input-style-2 hidden_previous" id="hidden_paymentBy"
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
                        @if($order->branch->foodics_status == 'true')
                            @if($branch->table == 'true' and $branch->table_payment != null)
                                <div class="input-style input-style-2 l">
                                    <label class="control-label"
                                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.choose') @lang('messages.payment_method')</label>
                                    <select name="payment_method" class="chosen-select {{$branch->id}}" onchange="showTablePaymentFatoourah(this)" required>
                                        <option value="default" disabled=""
                                                selected=""> @lang('messages.payment_method') </option>
                                        @if($branch->table_payment == 'both')
                                            @if($branch->receipt_payment == 'true')
                                                <option
                                                    value="receipt_payment"> @lang('messages.receipt_payment')</option>
                                            @endif
                                            @if($branch->online_payment == 'true')
                                                <option
                                                    value="online_payment"> @lang('messages.online_payment') </option>
                                            @endif
                                        @elseif($branch->receipt_payment == 'true' and $branch->table_payment == 'receipt')
                                            <option value="receipt_payment"> @lang('messages.receipt_payment') </option>
                                        @elseif($branch->online_payment == 'true' and $branch->table_payment == 'online')
                                            <option value="online_payment"> @lang('messages.online_payment') </option>
                                        @endif
                                    </select>
                                    @if ($errors->has('payment_method'))
                                        <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            @endif
                            @if($branch and $branch->payment_company == 'myFatoourah')
                                <div class="input-style input-style-2 hidden_previous" id="hidden_paymentBy"
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
                        @endif
                        <div class="content mt-0 pt-1">

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
                                                style="text-align: center!important; width: 100%; color:#fff; background-color:{{$restaurant->color == null ? '#f7b538' : $restaurant->color->icons}}!important "
                                                class="btn btn-l text-center  rounded-sm shadow-xl text-uppercase font-900">
                                            @lang('messages.depend_order')
                                        </button>
                                    @else
                                        {{--                                        <button type="submit" id="dependOrder" disabled--}}
                                        {{--                                                style="    width: 46.8%; color:#fff; background-color:#f7b538!important "--}}
                                        {{--                                                class="btn btn-l  rounded-sm shadow-xl text-uppercase font-900">--}}
                                        {{--                                            @lang('messages.depend_order')--}}
                                        {{--                                        </button>--}}
                                        <h5 class="text-center" style="color: red"> الطلبات غير متاحة في الوقت
                                            الحالي </h5>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </form>
                @endif
            </div>

    </div>
@if($items->count() == 0)
    <!--   <div class="card mr-0 ml-0 rounded-l">
            <div class="card header-card shape-rounded" style="min-height:100px;" data-card-height="100">
                <div class="card-overlay bg-highlight opacity-95"></div>
                <div class="card-overlay dark-mode-tint"></div>
                <div class="card-bg"></div>
            </div>
-->
        <div class="pb-5">
            <a href="{{route('sliverHomeTableBranch' , [$restaurant->name_barcode , $table->foodics_id != null ? $table->foodics_id : $table->name_barcode , $branch->name_barcode])}}"
               class="icon icon-xs rounded-xl color-white border-gray1-dark icon-border backwhiteop color-black"
               style="
                     position: absolute;
                     left: 11px;
                     top: 29px;z-index: 999;">
                <i class="fa fa-chevron-left"></i>
            </a>
        </div>
        <br>
        <br>
        <br>
        <h2 class="text-center"
            style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}"> @lang('messages.no_orders') </h2>
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
<script>
    $(document).ready(function () {
        $(".chosen-select").change(function(){
            $("#dependOrder").prop('disabled',false);
        });
    });
    function showTablePaymentFatoourah(element) {
        if (element.value == 'online_payment') {

            document.getElementById('hidden_paymentBy').style.display = element.value == 'online_payment' ? 'block' : 'none';
        }
    }
    $(function(){
        var paymentMethod = $('select[name=payment_method]');
        var count = 0;
        if(paymentMethod.find('option').length == 2){
            $.each(paymentMethod.find('option') , function(k , v){
                var i = $(v);
                if(!i.prop('disabled')){
                    paymentMethod.val(i.val());
                    paymentMethod.trigger('change');
                }
            });
        }


    });
</script>
<style>
    .branche {
        display: none;
    }

    body {
        position: relative;
    }

    .header-card {
        z-index: 1;
        top: -40px;
    }
    .m-selection select ,
    .input-style-2 select{
        background-color : #FFF;
        color : #495057 !important;
        border: 1px solid #ced4da;
        height: 35px;
        line-height: 25px;
        border-radius: 0.25rem;

        transition:  border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

    }
    .m-selection::after ,
    .input-style-2::after{
        color: #7e7e7e !important;
        content: '▼';
        position: absolute;
        top: 7px;
        left: 12px;
    }
    .input-style-2.l::after{
        top: 41px;
    }
    @if($restaurant->color)
    .card {
        background-color: {{$restaurant->color == null ? '' : $restaurant->color->background }};
    }

    .bg-white {
        background-color: {{$restaurant->color == null ? '#6a5353' : $restaurant->color->background }}     !important;
    }

    .card-overlay {
        background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background }};
    }

    .theme-light .color-theme {
        color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}    !important;
    }
    select{
        color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}    !important;
    }
    option{
        color:black;
    }
    @endif
</style>


@include('website.'.session('theme_path').'silver.layout.scripts')




