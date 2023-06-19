@extends($type.'.lteLayout.master')

@section('title')
    @if($branch->subscription == null)
        @lang('messages.add') @lang('messages.the_branches')
    @else
        @lang('messages.renewSubscription') @lang('messages.the_branches')
    @endif
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if($branch->subscription == null)
                        <h1> @lang('messages.add') @lang('messages.the_branches') </h1>
                    @else
                        <h1 class="card-title">@lang('messages.renewSubscription') @lang('messages.the_branches') </h1>
                    @endif
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('branches.index')}}">
                                @lang('messages.the_branches')
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
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            @if($branch->subscription == null)
                                <h3 class="card-title">@lang('messages.add') @lang('messages.the_branches') </h3>
                            @else
                                <h3 class="card-title">@lang('messages.renewSubscription') @lang('messages.the_branches') </h3>
                            @endif
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route($type == 'admin' ? 'storeBranchPayment' :'store_branch_payment' , $branch->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <?php $check_subscription = \App\Models\Subscription::whereBranchId($branch->id)->first(); ?>
                                @if($check_subscription)
                                    @if($check_subscription->end_at > Carbon\Carbon::now())
                                        <input type="hidden" name="package_id"
                                               value="{{$check_subscription->package->id}}">
                                        <input type="hidden" name="payment" value="true">
                                    @else
                                        <input type="hidden" name="package_id" value="1">
                                        <input type="hidden" name="payment" value="false">
                                    @endif
                                @else
                                    <input type="hidden" name="package_id" value="1">
                                    <input type="hidden" name="payment" value="false">
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.payment_method') </label>
                                    <select name="payment_method" class="form-control" onchange="showDiv(this)"
                                            required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="bank"> @lang('messages.bank_transfer') </option>
                                        <option value="online"> @lang('messages.online') </option>
                                    </select>
                                    @if ($errors->has('payment_method'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group" id="hidden_div" style="display: none;">
                                    <label class="control-label"> @lang('messages.payment_type') </label>
                                    <select name="payment_type" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
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
                                    <label class="control-label"> @lang('messages.seller_code') </label>
                                    <input type="text" name="seller_code" class="form-control"
                                           value="{{old('seller_code')}}"
                                           placeholder="{{app()->getLocale() == 'ar' ? 'أذا لديك كود خصم أكتبه هنا' : 'Put Your Seller Code Here'}}">
                                    @if ($errors->has('seller_code'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('seller_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.confirm')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('select[name="country_id"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/get/cities/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $('#register_city').empty();
                        // $('select[name="city_id"]').append("<option disabled selected> choose </option>");
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $('select[name="city_id"]').append("<option disabled selected> @lang('messages.choose_one') </option>");
                        $.each(data, function (index, cities) {
                            console.log(cities);
                            @if(app()->getLocale() == 'ar')
                            $('select[name="city_id"]').append('<option value="' + cities.id + '">' + cities.name_ar + '</option>');
                            @else
                            $('select[name="city_id"]').append('<option value="' + cities.id + '">' + cities.name_en + '</option>');
                            @endif
                        });
                    }
                });
            });
        });
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
        }

    </script>
    <script type="text/javascript">
        var map;

        function initMap() {

            var latitude = 24.774265; // YOUR LATITUDE VALUE
            var longitude = 46.738586;  // YOUR LONGITUDE VALUE


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

    <script>
        function showDiv(element) {
            if (element.value == 'online') {
                document.getElementById('hidden_div').style.display = element.value == 'online' ? 'block' : 'none';
            } else if (element.value == 'bank') {
                document.getElementById('hidden_div').style.display = element.value == 'bank' ? 'none' : 'none';
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('submit', 'form', function () {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
