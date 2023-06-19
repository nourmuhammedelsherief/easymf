@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.restaurant_orders_settings')
@endsection
@section('style')
    <style>
        #map {
            height: 600px;
            width: 1100px;
            position: relative;
            /* overflow: hidden;*/
        }

    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.restaurant_orders_settings') </h1>
                    <h3> @lang('messages.branch')  : ({{app()->getLocale() == 'ar' ?$setting->branch->name_ar :$setting->branch->name_en}})</h3>
                    <h3> @lang('messages.order_type') :
                        @if($setting->order_type == 'whatsapp')
                            @lang('dashboard.whatsapp_orders')
                        @elseif($setting->order_type == 'easymenu')
                            {{app()->getLocale() == 'ar' ? 'كاشير أيزي منيو' : 'EasyMenu Casher'}}
                        @endif
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant_setting.index')}}">
                                @lang('messages.restaurant_orders_settings')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                    @if($errors->any())
                        <p class="text-danger">{{$errors->first()}}</p>
                @endif
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.restaurant_orders_settings') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('restaurant_setting.update' , $setting->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div id="hidden_pre" style="display: {{in_array($setting->order_type , ['previous' , 'whatsapp' , 'easymenu']) ? 'block': 'none'}};">
                                    <div class="form-group">
                                        <span style="font-size:20px"> <b>(1)</b> </span>
                                        <label class="control-label"> @lang('messages.delivery') </label>
                                        <input name="delivery" type="radio" onclick="javascript:yesNoPreDeliveryCheck()" value="true" {{$setting->delivery == 'true' ? 'checked': ''}}
                                        placeholder="@lang('messages.delivery')"> @lang('messages.yes')
                                        <input name="delivery" id="preDeliveryCheck" onclick="javascript:yesNoPreDeliveryCheck()" type="radio" value="false" {{$setting->delivery == 'false' ? 'checked': ''}}
                                        placeholder="@lang('messages.delivery')"> @lang('messages.no')
                                        @if ($errors->has('delivery'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('delivery') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div id="ifYesPreDeliveryCheck" style="display:{{($setting->order_type == 'delivery' or $setting->delivery == 'true') ? 'block': 'none'}}">
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.delivery_value') </label>
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input name="pre_delivery_value" type="number" class="form-control"
                                                           value="{{$setting->delivery_value}}"
                                                           placeholder="@lang('messages.delivery_value')">
                                                </div>
                                                <div class="col-sm-2">
                                                    {{app()->getLocale() == 'ar' ? \Illuminate\Support\Facades\Auth::guard('restaurant')->user()->country->currency_ar : \Illuminate\Support\Facades\Auth::guard('restaurant')->user()->country->currency_en}}
                                                </div>
                                            </div>
                                            @if ($errors->has('pre_delivery_value'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('pre_delivery_value') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.order_delivery_distance') </label>
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input name="distance" type="number" class="form-control"
                                                           value="{{$setting->distance}}" placeholder="@lang('messages.order_distance')">
                                                </div>
                                                <div class="col-sm-2">
                                                    @lang('messages.km')
                                                </div>
                                            </div>

                                            @if ($errors->has('distance'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('distance') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.delivery_payment') </label>
                                            <select name="delivery_payment" class="form-control">
                                                <option disabled selected> @lang('messages.delivery_payment') </option>
                                                <option value="receipt" {{$setting->delivery_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                                <option value="online" {{$setting->delivery_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                                <option value="both" {{$setting->delivery_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
                                            </select>
                                            @if ($errors->has('delivery_payment'))
                                                <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('delivery_payment') }}
                                            </strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>


                                    <hr style="height:4px;border-width:0;color:gray;background-color:gray">
                                    <div class="form-group">
                                        <span style="font-size:20px"> <b>(2)</b> </span>
                                        <label class="control-label"> @lang('messages.takeaway') </label>
                                        <input name="takeaway" type="radio" value="true" onclick="javascript:yesNoPreTakeawayCheck()" {{$setting->takeaway == 'true' ? 'checked': ''}}
                                        placeholder="@lang('messages.takeaway')"> @lang('messages.yes')
                                        <input name="takeaway" type="radio" value="false" id="preTakeawayCheck" onclick="javascript:yesNoPreTakeawayCheck()" {{$setting->takeaway == 'false' ? 'checked': ''}}
                                        placeholder="@lang('messages.takeaway')"> @lang('messages.no')
                                        @if ($errors->has('takeaway'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('takeaway') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div id="ifYesPreTakeawayCheck" style="display:{{$setting->takeaway == 'true' ? 'block' : 'none'}}">
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.order_takeaway_distance') </label>
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input name="takeaway_distance" type="number" class="form-control"
                                                           value="{{$setting->takeaway_distance}}"
                                                           placeholder="@lang('messages.order_distance')">
                                                </div>
                                                <div class="col-sm-2">
                                                    @lang('messages.km')
                                                </div>
                                            </div>

                                            @if ($errors->has('takeaway_distance'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('takeaway_distance') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.takeaway_payment') </label>
                                            <select name="takeaway_payment" class="form-control">
                                                <option disabled selected> @lang('messages.takeaway_payment') </option>
                                                <option value="receipt" {{$setting->takeaway_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                                <option value="online" {{$setting->takeaway_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                                <option value="both" {{$setting->takeaway_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
                                            </select>
                                            @if ($errors->has('takeaway_payment'))
                                                <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('takeaway_payment') }}
                                            </strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr style="height:4px;border-width:0;color:gray;background-color:gray">
                                    <div class="form-group">
                                        <span style="font-size:20px"> <b>(3)</b> </span>
                                        <label class="control-label"> @lang('messages.previous') </label>
                                        <input name="previous" type="radio" value="true" {{$setting->previous == 'true' ? 'checked': ''}}
                                        id="previousYes" name="previous" onclick="javascript:previousYesNoCheck();"> @lang('messages.yes')
                                        <input name="previous" type="radio" value="false" {{$setting->previous == 'false' ? 'checked': ''}}
                                        onclick="javascript:previousYesNoCheck();"> @lang('messages.no')
                                        @if ($errors->has('previous'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('previous') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div id="previous_periods" style="display: {{$setting->previous == 'true' ? 'block':'none'}}">
                                        <a class="btn btn-success btn-sm" href="{{route('order_setting_days.index' , $setting->id)}}">
                                            <i class="fa fa-calendar-day"></i> @lang('messages.order_periods')
                                        </a>
                                        <a class="btn btn-secondary btn-sm" href="{{route('order_previous_days.index' , [$setting->branch->id,$setting->id])}}">
                                            <i class="fa fa-calendar-day"></i> @lang('messages.menu_periods')
                                        </a>

                                        <br>
                                        <br>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.order_previous_distance') </label>
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input name="previous_distance" type="number" class="form-control"
                                                           value="{{$setting->previous_distance}}"
                                                           placeholder="@lang('messages.order_previous_distance')">
                                                </div>
                                                <div class="col-sm-2">
                                                    @lang('messages.km')
                                                </div>
                                            </div>

                                            @if ($errors->has('previous_distance'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('previous_distance') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.previous_order_type') </label>
                                            <select name="previous_order_type" class="form-control">
                                                <option disabled selected> @lang('messages.previous_order_type') </option>
                                                <option value="takeaway" {{$setting->previous_order_type == 'takeaway' ? 'selected' : ''}}> @lang('messages.delivery') </option>
                                                <option value="delivery" {{$setting->previous_order_type == 'delivery' ? 'selected' : ''}}> @lang('messages.takeaway') </option>
                                                <option value="both" {{$setting->previous_order_type == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
                                            </select>
                                            @if ($errors->has('previous_order_type'))
                                                <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('previous_order_type') }}
                                            </strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.previous_payment') </label>
                                            <select name="previous_payment" class="form-control">
                                                <option disabled selected> @lang('messages.previous_payment') </option>
                                                <option value="receipt" {{$setting->previous_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                                <option value="online" {{$setting->previous_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                                <option value="both" {{$setting->previous_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
                                            </select>
                                            @if ($errors->has('previous_payment'))
                                                <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('previous_payment') }}
                                            </strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr style="height:4px;border-width:0;color:gray;background-color:gray">
                                    <br>

                                </div>


                                <div class="form-group">
                                    <span style="font-size:20px"> <b>(4)</b> </span>
                                    <label class="control-label"> @lang('messages.table_orders') </label>
                                    <input name="table" type="radio" value="true"
                                        {{$setting->table == 'true' ? 'checked': ''}} onclick="javascript:yesNoTableCheck();"> @lang('messages.yes')
                                    <input name="table" type="radio" value="false"
                                        {{$setting->table == 'false' ? 'checked': ''}}
                                        id="tableCheckID" onclick="javascript:yesNoTableCheck();"> @lang('messages.no')
                                    @if ($errors->has('table'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('table') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="IfYesTableCheck" style="display: {{$setting->table == 'true' ? 'block':'none' }}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.table_payment') </label>
                                        <select name="table_payment" class="form-control">
                                            <option disabled selected> @lang('messages.table_payment') </option>
                                            <option value="receipt" {{$setting->table_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                            <option value="online" {{$setting->table_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                            <option value="both" {{$setting->table_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
                                        </select>
                                        @if ($errors->has('table_payment'))
                                            <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('table_payment') }}
                                            </strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <hr style="height:4px;border-width:0;color:gray;background-color:gray">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.receipt_payment') </label>
                                    <input name="receipt_payment" type="radio" {{$setting->receipt_payment == 'true' ? 'checked' : ''}} value="true"> @lang('messages.yes')
                                    <input name="receipt_payment" type="radio" {{$setting->receipt_payment == 'false' ? 'checked' : ''}} value="false"> @lang('messages.no')
                                    @if ($errors->has('receipt_payment'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('receipt_payment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if($setting->order_type == 'whatsapp')
                                    <div class="form-group bank-transfer">
                                        <label class="control-label"> @lang('messages.bank_transfer') </label>
                                        <input name="bank_transfer" {{$setting->bank_transfer == 'true' ? 'checked' : ''}} type="radio" value="true"> @lang('messages.yes')
                                        <input name="bank_transfer" {{$setting->bank_transfer == 'false' ? 'checked' : ''}} type="radio" value="false"> @lang('messages.no')
                                        @if ($errors->has('bank_transfer'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('bank_transfer') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.online_payment') </label>
                                    <input name="online_payment" {{$setting->online_payment == 'true' ? 'checked' : ''}} onclick="javascript:yesnoCheck();" id="noCheck"
                                           type="radio" value="true"> @lang('messages.yes')
                                    <input name="online_payment" {{$setting->online_payment == 'false' ? 'checked' : ''}} onclick="javascript:yesnoCheck();" id="yesCheck"
                                           type="radio" value="false"> @lang('messages.no')
                                    @if ($errors->has('online_payment'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('online_payment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if($setting->order_type == 'whatsapp')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('dashboard.entry.whatsapp_number') </label>
                                        <input name="whatsapp_number" class="form-control" type="tel" value="{{$setting->whatsapp_number}}"
                                               placeholder="+966xxxxxxxxx">
                                        @if ($errors->has('whatsapp_number'))
                                            <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('whatsapp_number') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                @endif

                                <div id="ifYes" style="display:{{$setting->online_payment == 'true' ? 'block' : 'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.payment_company') </label>
                                        <select name="payment_company" class="form-control">
                                            <option disabled selected> @lang('messages.choose_payment_company') </option>
                                            <option value="myFatoourah" {{$setting->payment_company == 'myFatoourah' ? 'selected' : ''}}>@lang('messages.myFatoourah')</option>
                                            <option value="tap" {{$setting->payment_company == 'tap' ? 'selected' : ''}}>@lang('messages.tap')</option>
                                            <option value="express" {{$setting->payment_company == 'express' ? 'selected' : ''}}>@lang('messages.express')</option>
                                        </select>
                                        @if ($errors->has('payment_company'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_company') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div id="online_token" style="display: {{($setting->payment_company == 'tap' || $setting->payment_company == 'myFatoourah') ? 'block':'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.online_token') </label>
                                        <input name="online_token" type="text" class="form-control"
                                               value="{{$setting->online_token}}"
                                               placeholder="@lang('messages.online_token')">
                                        @if ($errors->has('online_token'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('online_token') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div id="express_keys" style="display: {{$setting->payment_company == 'express' ? 'block' : 'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.merchant_key') </label>
                                        <input name="merchant_key" type="text" class="form-control"
                                               value="{{$setting->merchant_key}}"
                                               placeholder="@lang('messages.merchant_key')">
                                        @if ($errors->has('online_token'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('merchant_key') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.express_password') </label>
                                        <input name="express_password" type="text" class="form-control"
                                               value="{{$setting->express_password}}"
                                               placeholder="@lang('messages.express_password')">
                                        @if ($errors->has('express_password'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('express_password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group">
                                    <h4 style="text-align: right">  @lang('messages.selectBranchPosition')  </h4>
                                    <input type="text" id="lat" name="latitude"
                                           value="{{$setting->branch->latitude}}"
                                           readonly="yes" required>
                                    <input type="text" id="lng" name="longitude"
                                           value="{{$setting->branch->longitude}}"
                                           readonly="yes" required>
                                    <a class="btn btn-info"
                                       onclick="getLocation()"> @lang('messages.MyPosition') </a>
                                    @if ($errors->has('latitude'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>
                                        </span>
                                    @endif
                                    <hr>

                                    <div id="map"
                                         style="position: relative; height: 600px; width: 600px; "></div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
{{--<script>--}}
{{--    function showDiv(element) {--}}
{{--        if (element.value == 'delivery') {--}}
{{--            document.getElementById('hidden_div').style.display = element.value == 'delivery' ? 'block' : 'none';--}}
{{--        } else {--}}
{{--            document.getElementById('hidden_div').style.display = 'none';--}}
{{--        }--}}
{{--    }--}}
{{--</script>--}}
<script type="text/javascript">

    function yesnoCheck() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'none';
        } else {
            document.getElementById('ifYes').style.display = 'block';
        }
    }
</script>
<script type="text/javascript">

    function yesNoPreDeliveryCheck() {
        if (document.getElementById('preDeliveryCheck').checked) {
            document.getElementById('ifYesPreDeliveryCheck').style.display = 'none';
        } else {
            document.getElementById('ifYesPreDeliveryCheck').style.display = 'block';
        }
    }
    function yesNoPreTakeawayCheck() {
        if (document.getElementById('preTakeawayCheck').checked) {
            document.getElementById('ifYesPreTakeawayCheck').style.display = 'none';
        } else {
            document.getElementById('ifYesPreTakeawayCheck').style.display = 'block';
        }
    }
</script>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        lat = position.coords.latitude;
        lon = position.coords.longitude;

        document.getElementById('lat').value = lat; //latitude
        document.getElementById('lng').value = lon; //longitude
        latlon = new google.maps.LatLng(lat, lon)
        mapholder = document.getElementById('mapholder')
        //mapholder.style.height='250px';
        //mapholder.style.width='100%';

        var myOptions = {
            center: latlon, zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL}
        };
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
        var marker = new google.maps.Marker({position: latlon, map: map, title: "You are here!"});
        //Listen for any clicks on the map.
        google.maps.event.addListener(map, 'click', function (event) {
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if (marker === false) {
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });
                //Listen for drag events!
                google.maps.event.addListener(marker, 'dragend', function (event) {
                    markerLocation();
                });
            } else {
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            markerLocation();
        });


        function markerLocation() {
            //Get location.
            var currentLocation = marker.getPosition();
            //Add lat and lng values to a field that we can save.
            document.getElementById('lat').value = currentLocation.lat(); //latitude
            document.getElementById('lng').value = currentLocation.lng(); //longitude
        }
    }
    function previousYesNoCheck() {
        if (document.getElementById('previousYes').checked) {
            document.getElementById('previous_periods').style.display = 'block';
        } else {
            document.getElementById('previous_periods').style.display = 'none';
        }
    }
    function yesNoTableCheck() {
        if (document.getElementById('tableCheckID').checked) {
            document.getElementById('IfYesTableCheck').style.display = 'none';
        } else {
            document.getElementById('IfYesTableCheck').style.display = 'block';
        }
    }
</script>

<script type="text/javascript">
    var map;

    function initMap() {

        var latitude = {{$setting->branch->latitude}}; // YOUR LATITUDE VALUE
        var longitude = {{$setting->branch->longitude}};  // YOUR LONGITUDE VALUE

        var myLatLng = {lat: latitude, lng: longitude};

        map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 5,
            gestureHandling: 'true',
            zoomControl: false// disable the default map zoom on double click
        });


        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            //title: 'Hello World'

            // setting latitude & longitude as title of the marker
            // title is shown when you hover over the marker
            title: latitude + ', ' + longitude
        });


        //Listen for any clicks on the map.
        google.maps.event.addListener(map, 'click', function (event) {
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if (marker === false) {
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });
                //Listen for drag events!
                google.maps.event.addListener(marker, 'dragend', function (event) {
                    markerLocation();
                });
            } else {
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            markerLocation();
        });


        function markerLocation() {
            //Get location.
            var currentLocation = marker.getPosition();
            //Add lat and lng values to a field that we can save.
            document.getElementById('lat').value = currentLocation.lat(); //latitude
            document.getElementById('lng').value = currentLocation.lng(); //longitude
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFUMq5htfgLMNYvN4cuHvfGmhe8AwBeKU&callback=initMap"
        async defer></script>

@push('scripts')
    <script>
        $(function () {

            $('select[name=order_type]').on('change', function () {

                var tag = $(this);
                if (tag.val() == 'whatsapp') {
                    $('input[name=whatsapp_number]').parent().slideDown(300);
                    $('.form-group.bank-transfer').fadeIn(300);
                } else {
                    $('input[name=whatsapp_number]').parent().slideUp(300);
                    $('.form-group.bank-transfer').fadeOut(300);
                }
            });

            $('select[name=payment_company]').on('change', function () {
                if ($(this).val() == 'express') {
                    document.getElementById('express_keys').style.display = 'block';
                    document.getElementById('online_token').style.display = 'none';
                } else {
                    document.getElementById('online_token').style.display = 'block';
                    document.getElementById('express_keys').style.display = 'none';
                }
            });
        });
    </script>
@endpush
