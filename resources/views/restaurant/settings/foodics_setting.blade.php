@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') {{app()->getLocale() == 'ar' ? 'إعدادت طلبات فودكس': 'Foodics Order Setting'}}
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
                    <h1> @lang('messages.edit') {{app()->getLocale() == 'ar' ? 'إعدادت طلبات فودكس': 'Foodics Order Setting'}} </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('FoodicsOrderSetting' , $branch->id)}}">
                                {{app()->getLocale() == 'ar' ? 'إعدادت طلبات فودكس': 'Foodics Order Setting'}}
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
{{--                @include('flash::message')--}}
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') {{app()->getLocale() == 'ar' ? 'إعدادت طلبات فودكس': 'Foodics Order Setting'}} </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('updateFoodicsOrderSetting' , $branch->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <span style="font-size:20px"> <b>(1)</b> </span>
                                    <label class="control-label"> @lang('messages.delivery') </label>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="radio" name="delivery" value="true" onclick="javascript:yesnoDelivery();" value="true" {{$branch->delivery == 'true' ? 'checked':''}}> @lang('messages.yes')
                                            <input type="radio" name="delivery" value="false" id="yesDelivery" value="false" onclick="javascript:yesnoDelivery();" {{$branch->delivery == 'false' ? 'checked':''}}> @lang('messages.no')
                                        </div>
                                    </div>
                                    @if ($errors->has('delivery'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('delivery') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="DeliveryCheck" style="display: {{$branch->delivery == 'true' ? 'block' : 'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.order_distance') </label>
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="delivery_distance" value="{{$branch->delivery_distance}}">
                                            </div>
                                            <div class="col-sm-2">
                                                @lang('messages.km')
                                            </div>
                                        </div>

                                        @if ($errors->has('delivery_distance'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('delivery_distance') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.delivery_payment') </label>
                                        <select name="delivery_payment" class="form-control">
                                            <option disabled selected> @lang('messages.delivery_payment') </option>
                                            <option value="receipt" {{$branch->delivery_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                            <option value="online" {{$branch->delivery_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                            <option value="both" {{$branch->delivery_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
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
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="radio" name="takeaway" onclick="javascript:yesnoTakeaway();" value="true" {{$branch->takeaway == 'true' ? 'checked':''}}> @lang('messages.yes')
                                            <input type="radio" name="takeaway" id="yesTakeaway" onclick="javascript:yesnoTakeaway();" value="false" {{$branch->takeaway == 'false' ? 'checked':''}}> @lang('messages.no')
                                        </div>
                                    </div>

                                    @if ($errors->has('takeaway'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('takeaway') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="takeawayCheck" style="display: {{$branch->takeaway == 'true' ? 'block' : 'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.order_distance') </label>
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="takeaway_distance" value="{{$branch->takeaway_distance}}">
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
                                            <option value="receipt" {{$branch->takeaway_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                            <option value="online" {{$branch->takeaway_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                            <option value="both" {{$branch->takeaway_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
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
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="radio" id="previousYes" name="previous" onclick="javascript:previousYesNoCheck();" value="true" {{$branch->previous == 'true' ? 'checked':''}}> @lang('messages.yes')
                                            <input type="radio" name="previous" onclick="javascript:previousYesNoCheck();" value="false" {{$branch->previous == 'false' ? 'checked':''}}> @lang('messages.no')
                                        </div>
                                    </div>

                                    @if ($errors->has('previous'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('previous') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="previous_periods" style="display: {{$branch->previous == 'true' ? 'block':'none'}}">
                                    <a class="btn btn-success btn-lg" href="{{route('order_foodics_days.index' , $branch->id)}}">
                                        <i class="fa fa-calendar-day"></i> @lang('messages.order_periods')
                                    </a>
                                    <a class="btn btn-secondary btn-lg" href="{{route('menu_foodics_days.index' , $branch->id)}}">
                                        <i class="fa fa-calendar-day"></i> @lang('messages.menu_periods')
                                    </a>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.order_previous_distance') </label>
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <input name="previous_distance" type="number" class="form-control"
                                                       value="{{$branch->previous_distance}}"
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
                                            <option value="delivery" {{$branch->previous_order_type == 'delivery' ? 'selected' : ''}}> @lang('messages.delivery') </option>
                                            <option value="takeaway" {{$branch->previous_order_type == 'takeaway' ? 'selected' : ''}}> @lang('messages.takeaway') </option>
                                            <option value="both" {{$branch->previous_order_type == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
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
                                            <option value="receipt" {{$branch->previous_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                            <option value="online" {{$branch->previous_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                            <option value="both" {{$branch->previous_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
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
                                <div class="form-group">
                                    <span style="font-size:20px"> <b>(4)</b> </span>
                                    <label class="control-label"> @lang('messages.table_orders') </label>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="radio" name="table" value="true" {{$branch->table == 'true' ? 'checked':''}} onclick="javascript:yesNoTableCheck();"> @lang('messages.yes')
                                            <input type="radio" name="table" value="false" {{$branch->table == 'false' ? 'checked':''}} id="tableCheckID" onclick="javascript:yesNoTableCheck();"> @lang('messages.no')
                                        </div>
                                    </div>

                                    @if ($errors->has('table'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('table') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="IfYesTableCheck" style="display: {{$branch->table == 'true' ? 'block':'none' }}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.table_payment') </label>
                                        <select name="table_payment" class="form-control">
                                            <option disabled selected> @lang('messages.table_payment') </option>
                                            <option value="receipt" {{$branch->table_payment == 'receipt' ? 'selected' : ''}}> @lang('messages.receipt_payment') </option>
                                            <option value="online" {{$branch->table_payment == 'online' ? 'selected' : ''}}> @lang('messages.online_payment') </option>
                                            <option value="both" {{$branch->table_payment == 'both' ? 'selected' : ''}}> @lang('messages.both') </option>
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

                                <br>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.receipt_payment') </label>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="radio" name="receipt_payment" value="true" {{$branch->receipt_payment == 'true' ? 'checked':''}}> @lang('messages.yes')
                                            <input type="radio" name="receipt_payment" value="false" {{$branch->receipt_payment == 'false' ? 'checked':''}}> @lang('messages.no')
                                        </div>
                                    </div>

                                    @if($errors->has('receipt_payment'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('receipt_payment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.online_payment') </label>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="radio" name="online_payment" id="noCheck" value="true" onclick="javascript:yesnoCheck();" {{$branch->online_payment == 'true' ? 'checked':''}}> @lang('messages.yes')
                                            <input type="radio" name="online_payment" id="yesCheck" value="false" onclick="javascript:yesnoCheck();" {{$branch->online_payment == 'false' ? 'checked':''}}> @lang('messages.no')
                                        </div>
                                    </div>

                                    @if ($errors->has('online_payment'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('online_payment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="ifYes" style="display:{{$branch->online_payment == 'true' ? 'block' : 'none' }}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.payment_company') </label>
                                        <select name="payment_company" class="form-control">
                                            <option disabled selected> @lang('messages.choose_payment_company') </option>
                                            <option value="myFatoourah" {{$branch->payment_company == 'myFatoourah' ? 'selected' : ''}}>@lang('messages.myFatoourah')</option>
                                            <option value="tap" {{$branch->payment_company == 'tap' ? 'selected' : ''}}>@lang('messages.tap')</option>
                                            <option value="express" {{$branch->payment_company == 'express' ? 'selected' : ''}}>@lang('messages.express')</option>
                                        </select>
                                        @if ($errors->has('payment_company'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_company') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div id="online_token" style="display: {{($branch->payment_company == 'tap' || $branch->payment_company == 'myFatoourah') ? 'block':'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.online_token') </label>
                                        <input name="online_token" type="text" class="form-control"
                                               value="{{$branch->online_token}}"
                                               placeholder="@lang('messages.online_token')">
                                        @if ($errors->has('online_token'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('online_token') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div id="express_keys" style="display: {{$branch->payment_company == 'express' ? 'block' : 'none'}}">
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.merchant_key') </label>
                                        <input name="merchant_key" type="text" class="form-control"
                                               value="{{$branch->merchant_key}}"
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
                                               value="{{$branch->express_password}}"
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
                                           value="{{$branch->latitude}}"
                                           readonly="yes" >
                                    <input type="text" id="lng" name="longitude"
                                           value="{{$branch->longitude}}"
                                           readonly="yes" >
                                    <a class="btn btn-info"
                                       onclick="getLocation()"> @lang('messages.MyPosition') </a>
                                    @if ($errors->has('latitude'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>
                                        </span>
                                    @endif
                                    <hr>

                                    <div id="map" style="position: relative; height: 600px; width: 600px; "></div>
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
    <script type="text/javascript">

        function yesnoCheck() {
            if (document.getElementById('yesCheck').checked) {
                document.getElementById('ifYes').style.display = 'none';
            } else {
                document.getElementById('ifYes').style.display = 'block';
            }
        }
        function yesnoDelivery() {
            if (document.getElementById('yesDelivery').checked) {
                document.getElementById('DeliveryCheck').style.display = 'none';
            } else {
                document.getElementById('DeliveryCheck').style.display = 'block';
            }
        }
        function yesnoTakeaway() {
            if (document.getElementById('yesTakeaway').checked) {
                document.getElementById('takeawayCheck').style.display = 'none';
            } else {
                document.getElementById('takeawayCheck').style.display = 'block';
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
    </script>

    <script type="text/javascript">
        var map;

        function initMap() {

            var latitude = {{$branch->latitude}}; // YOUR LATITUDE VALUE
            var longitude = {{$branch->longitude}};  // YOUR LONGITUDE VALUE

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
@endsection

@push('scripts')
    <script>
        $(function () {
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
