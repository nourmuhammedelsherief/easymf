@include('website.'.session('theme_path').'silver.layout.header')
{{--@include('website.'.session('theme_path').'silver.layout.head')--}}

<?php  $order_price = 0; ?>
<style>
    body {
        position: relative;
    }

    .header-card {
        z-index: 1;
        top: -40px;
    }
    .order-d {
        margin-bottom: 20px;
    }
    .order-d .delete-order{
        background: red !important;
    }
</style>
<div class="card header-card  shape-rounded" style="min-height:100px;" data-card-height="100">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg"></div>
</div>

<div class="card cart-content mr-0 ml-0 rounded-l" style="margin-top:40px;">

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
                    <div class="order-d col-12">
                        <div class="row">

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

                        </div>
                        {{-- <a class="delete-order" href="{{route('removeSilverCartOrder' , $order->id)}}">
                            <span><i class="far fa-trash-alt"></i></span>
                        </a> --}}
                    </div>
                    <hr style="width: 100%;"/>
                    <?php  $order_price += $order->order_price; ?>
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
                <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                @if($branch->tax == 'true')
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

                <div class="col-4">
                    <p class="font-14 font-700 color-theme">@lang('messages.total')</p>
                </div>
                <div class="col-8 text-left">
                    <p class="font-13 font-600 color-theme">
                        @if($branch->tax == 'true')
                            {{$order_price + (($branch->tax_value * $order_price) / 100)}}
                        @else
                            {{$order_price}}
                        @endif
                        {{app()->getLocale() == 'ar' ? $branch->country->currency_ar : $branch->country->currency_en}}
                    </p>
                </div>

            </div>
            <br>
            @if($branch->foodics_status == 'true' && $restaurant->foodics_access_token != null)
                <form method="post" action="{{route('FoodicsOrder' , $branch->id)}}">
                    @csrf
                    <input type="hidden" id="lat" name="latitude" value="" readonly="yes" required>
                    <input type="hidden" id="lng" name="longitude" value="" readonly="yes" required>
                    <div class="form-group">
                        <label for="branch_id" class="color-dark1-dark font-15">
                            @lang('messages.choose_branch')
                        </label>
                        @php
                            $foodics_branches = \App\Models\RestaurantFoodicsBranch::whereRestaurantId($restaurant->id)
                                ->where('branch_id' , $branch->id)
                                ->where('active' , 'true')
                                ->get();
                        @endphp
                        <select name="branch_id" class="form-control">
                            @foreach($foodics_branches as $foodics_branch)
                                <option value="{{$foodics_branch->id}}">
                                    {{app()->getLocale() == 'ar' ? $foodics_branch->name_ar : $foodics_branch->name_en}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_type" class="color-dark1-dark font-15">
                            @lang('messages.order_type')
                        </label>
                        <select name="order_type" onchange="showDivPrevious(this)" class="form-control">
                            @if($branch->takeaway == 'true')
                                <option value="takeaway"> @lang('messages.takeaway') </option>
                            @endif

                            @if($branch->delivery == 'true')
                                <option value="delivery"> @lang('messages.delivery') </option>
                            @endif
                            @php
                                $carbon = \Carbon\Carbon::now();
                                $current_day = $carbon->format('l');
                                $day = \App\Models\Day::where('name_en' , $current_day)->first();
                                $foodicsPreviousPeriod = \App\Models\RestaurantOrderPeriod::with('days')
                                    ->whereHas('days' , function ($q) use ($day){
                                        $q->where('day_id' , $day->id);
                                    })
                                    ->whereRestaurantId($restaurant->id)
                                    ->where('type', 'foodics')
                                    ->where('branch_id', $branch->id)
                                    ->first();
                            @endphp
                            @if($branch->previous == 'true' and ($foodicsPreviousPeriod and $foodicsPreviousPeriod->start_at <= $carbon->format('H:i:s') and $foodicsPreviousPeriod->end_at >= $carbon->format('H:i:s')))
                                <option value="previous"> @lang('messages.previous') </option>
                            @endif
                        </select>
                    </div>
                    <div id="previous_periods" style="display: none">
                        <label> @lang('messages.order_type') </label>
                        <br>
                        <input type="radio" value="delivery" name="previous_order_type"> @lang('messages.delivery')
                        <br>
                        <input type="radio" value="takeaway" name="previous_order_type"> @lang('messages.takeaway')
                        @if ($errors->has('previous_order_type'))
                            <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('previous_order_type') }}</strong>
                            </span>
                        @endif
                        <?php $periods = \App\Models\RestaurantOrderPeriod::whereBranchId($branch->id)
                            ->where('setting_id', null)
                            ->where('type', 'foodics_order')
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
                            @if ($errors->has('period_id'))
                                <span class="help-block">
                                    <strong style="color: red;">{{ $errors->first('period_id') }}</strong>
                                </span>
                            @endif
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
                    @php
                        $online_service = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                        ->where('service_id' , 3)
                        ->where('status' , 'active')
                        ->first();
                    @endphp
                    <div class="form-group">
                        <label for="order_type" class="color-dark1-dark font-15">
                            @lang('messages.payment_method')
                        </label>
                        <select name="payment_method" class="form-control" onchange="showDiv(this)">
                            @if($branch->receipt_payment == 'true')
                                <option value="EasyMenu-cash"> @lang('messages.receipt_payment') </option>
                            @endif
                            @if($online_service or $branch->online_payment == 'true')
                                <option value="EasyMenu-online"> @lang('messages.online') </option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group" id="hidden_div" style="display: none;">
                        <label class="control-label"> @lang('messages.payment_type') </label>
                        <select name="online_type" class="form-control" required>
                            <option value="visa"> @lang('messages.visa') </option>
                            <option value="mada"> @lang('messages.mada') </option>
                            <option value="apple_pay"> @lang('messages.apple_pay') </option>
                        </select>
                        @if ($errors->has('payment_type'))
                            <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('payment_type') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label"> @lang('messages.foodics_discount') </label>
                        <input type="text" name="discount_name" value="{{old('discount_name')}}" class="form-control" placeholder="{{app()->getLocale() == 'ar' ? 'أذا كان لديك كود خصم فودكس' : 'Put Your Foodics Seller Code Here'}}">
                        @if ($errors->has('discount_name'))
                            <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('discount_name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="text-center mt-3 mb-3" id="position_id">
                        <a onclick="getLocation()" id="locat"
                           style="    width: 100%; color:#fff; background-color:#f7b538!important "
                           class="btn btn-l  rounded-sm shadow-xl text-uppercase font-900">
                            <i class="fas fa-map-marker-alt"></i> @lang('messages.MyPosition')
                        </a>
                    </div>
                    <div id="showPositionResult" class="text-center"></div>
                    <div class="text-center" id="dependOrder">
                        @if($branch->state == 'closed')
                            <button disabled class="btn btn-l bg-success rounded-sm shadow-xl text-uppercase font-900"
                                    type="submit">
                                @lang('messages.depend_order')
                            </button>
                            <h5 class="text-center" style="color: red"> المطعم مغلق </h5>
                        @elseif($branch->state == 'busy')
                            <button disabled class="btn btn-l bg-success rounded-sm shadow-xl text-uppercase font-900"
                                    type="submit">
                                @lang('messages.depend_order')
                            </button>
                            <h5 class="text-center" style="color: red"> المطعم مشغول </h5>
                        @else
                            @if(check_branch_periods($branch->id))
                                <button  class="btn btn-l bg-success rounded-sm shadow-xl text-uppercase font-900"
                                         type="submit">
                                    @lang('messages.depend_order')
                                </button>
                            @else
                                <button disabled class="btn btn-l bg-success rounded-sm shadow-xl text-uppercase font-900"
                                        type="submit">
                                    @lang('messages.depend_order')
                                </button>
                                <h5 class="text-center" style="color: red"> الطلبات غير متاحة في الوقت الحالي </h5>
                            @endif
                        @endif
                    </div>
                    <br>
                </form>
            @endif
            <div class="text-center">
                <a href="{{route('emptySilverCart')}}"
                   class="btn btn-l bg-highlight rounded-sm shadow-xl text-uppercase font-900">@lang('messages.emptyCart')</a>
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
{{--@include('website.'.session('theme_path').'silver.layout.footer')--}}

<script>
    $(document).ready(function () {
        'use strict'
        $('.menu-hider, .close-menu, .menu-close').on('click', function () {
            $('.menu').removeClass('menu-active');
            $('.menu-hider').removeClass('menu-active menu-active-clear');
            $('.header, .page-content, #footer-bar').css('transform', 'translate(0,0)');
            $('.menu-hider').css('transform', 'translate(0,0)');
            $('#footer-bar').removeClass('footer-menu-hidden');
            $('body').removeClass('modal-open');
            return false;
        });

        $('input[type="radio"]').click(function () {
            var val = $('input[name=previous_order_type]:checked').val();
            if (val == 'delivery' || val == 'takeaway' ) {
                document.getElementById('position_id').style.display = 'block';
            } else {
                document.getElementById('position_id').style.display = 'none';
            }
        });
    });

    function showDivPrevious(element) {
        if (element.value == 'delivery' || element.value == 'takeaway') {
            document.getElementById('position_id').style.display = 'block';
            document.getElementById('previous_periods').style.display = 'none';
        }
        if (element.value == 'previous') {
            document.getElementById('position_id').style.display = 'none';
            document.getElementById('previous_periods').style.display = 'block';
        }

    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                // Success function
                showPosition,
                // Error function
                null,
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
        {{--$.ajax({--}}
        {{--    type: 'GET', //THIS NEEDS TO BE GET--}}
        {{--    url: '/user/{{$branch->id}}/foodics_show_position/'+position.coords.latitude+'/'+position.coords.longitude,--}}
        {{--    dataType: 'json',--}}
        {{--    success: function (data) {--}}
        {{--        if (data.status == false)--}}
        {{--        {--}}
        {{--            document.getElementById("showPositionResult").style.color = 'red';--}}
        {{--            document.getElementById("showPositionResult").innerHTML = data.data;--}}
        {{--            document.getElementById("dependOrder").style.display = 'none';--}}
        {{--        }else{--}}
        {{--            document.getElementById("showPositionResult").style.color = 'green';--}}
        {{--            document.getElementById("showPositionResult").innerHTML = data.data;--}}
        {{--        }--}}
        {{--    },error:function(){--}}
        {{--        console.log(data);--}}
        {{--    }--}}
        {{--});--}}
    }

    function showDiv(element) {
        document.getElementById('hidden_div').style.display = element.value == 'EasyMenu-online' ? 'block' : 'none';
    }

</script>
<script>
    $(document).ready(function () {
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
    });
</script>

@include('website.'.session('theme_path').'silver.layout.scripts')
