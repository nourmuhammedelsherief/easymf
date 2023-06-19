

<meta name="csrf-token" content="{{ csrf_token() }}">
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>--}}
<!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
    .error {
        color: red;
        display: block !important;
    }

    label {
        display: block;
        width: 100%;
        font-size: 15px;
    }
    /*#map {*/
    /*    height: 350px;*/
    /*    width: 335px;*/
    /*}*/
</style>


<div class="login-box">
    <div class="login-logo">
        <a href="{{route('restaurant.step1Register')}}"><b>@lang('messages.restaurant_register')</b></a>
    </div>
    <div class="card">
        @if (session('An_error_occurred'))
            <div class="alert alert-success">
                {{ session('An_error_occurred') }}
            </div>
        @endif
        @if (session('warning_login'))
            <div class="alert alert-danger">
                {{ session('warning_login') }}
            </div>
        @endif
        @include('flash::message')
        <div class="alert alert-danger" style="display:none"></div>

        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form id="post-form" method="post" action="{{route('restaurant.submitStep2' , $restaurant->id)}}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success d-none" id="msg_div">
                            <span id="res_message"></span>
                        </div>
                    </div>
                </div>
                <h6 class="text-right"> {{app()->getLocale() == 'ar' ? 'أختر تصنيف' : 'Choose Category'}} </h6>
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-sliders-h"></span>
                        </div>
                    </div>
                    <select class="select2" multiple="multiple"
                            data-placeholder="{{trans('messages.choose_category')}}" name="category_id[]">
                        <option disabled> @lang('messages.choose_category') </option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">
                                @if(app()->getLocale() == 'ar')
                                    {{$category->name_ar}}
                                @else
                                    {{$category->name_en}}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('category_id'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $errors->first('category_id') }}</span>
                        </div>
                    @endif
                </div>
                <h6 class="text-right"> {{app()->getLocale() == 'ar' ? ' المدينة' : 'Choose City'}} </h6>

                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-flag"></span>
                        </div>
                    </div>
                    <select class="form-control" name="city_id">
                        <option disabled selected> @lang('messages.choose_city') </option>
                        @foreach($cities as $city)
                            <option value="{{$city->id}}">
                                @if(app()->getLocale() == 'ar')
                                    {{$city->name_ar}}
                                @else
                                    {{$city->name_en}}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('city_id'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $errors->first('city_id') }}</span>
                        </div>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-flag"></span>
                        </div>
                    </div>
                    <select class="form-control" name="package_id">
                        <option disabled selected> {{app()->getLocale() == 'ar' ? 'اختر الباقة' : 'Choose Package'}} </option>
                        @foreach($packages as $package)
                            <option value="{{$package->id}}">
                                @if(app()->getLocale() == 'ar')
                                    {{$package->name_ar}}
                                @else
                                    {{$package->name_en}}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('package_id'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $errors->first('package_id') }}</span>
                        </div>
                    @endif
                </div>
                {{--                    <h6 class="text-right"> {{app()->getLocale() == 'ar' ? 'أكتب أسم رابط المطعم' : 'BarCode Name'}} </h6>--}}
                {{--                    <h6 style="color: red"> @lang('messages.name_barcode_rules') </h6>--}}

                {{--                    <div class="input-group mb-3">--}}
                {{--                        <div class="input-group-append">--}}
                {{--                            <div class="input-group-text">--}}
                {{--                                <span class="fas fa-barcode"></span>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        <input type="text" name="name_barcode"  class="form-control" value="{{old('name_barcode')}}" placeholder="@lang('messages.name_barcode')">--}}

                {{--                        @if ($errors->has('name_barcode'))--}}
                {{--                            <div class="alert alert-danger">--}}
                {{--                                <button class="close" data-close="alert"></button>--}}
                {{--                                <span> {{ $errors->first('name_barcode') }}</span>--}}
                {{--                            </div>--}}
                {{--                        @endif--}}
                {{--                    </div>--}}
                {{--                    <h6 class="text-right"> {{app()->getLocale() == 'ar' ? 'أكواد الخصم' : 'Seller Codes'}} </h6>--}}

                {{--                    <div class="input-group mb-3">--}}
                {{--                        <div class="input-group-append">--}}
                {{--                            <div class="input-group-text">--}}
                {{--                                <span class="fas fa-user"></span>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        <input type="text" name="seller_code"  class="form-control" value="{{old('seller_code')}}" placeholder="{{app()->getLocale() == 'ar' ? 'أذا لديك كود خصم أكتبه هنا' : 'Put Your Seller Code Here'}}">--}}

                {{--                        @if ($errors->has('seller_code'))--}}
                {{--                            <div class="alert alert-danger">--}}
                {{--                                <button class="close" data-close="alert"></button>--}}
                {{--                                <span> {{ $errors->first('seller_code') }}</span>--}}
                {{--                            </div>--}}
                {{--                        @endif--}}
                {{--                    </div>--}}


                {{--                    <div class=" form-group " id="hide-map">--}}


                {{--                        <div class="content sections">--}}


                {{--                            <div--}}
                {{--                                class="wrap-title d-flex justify-content-between mm">--}}

                {{--                                <a class="btn btn-success " onclick="getLocation();">--}}
                {{--                                    <i class="fa fa-location-arrow"> </i>--}}
                {{--                                    @lang('messages.specify_my_location')--}}
                {{--                                </a>--}}

                {{--                            </div>--}}


                {{--                            <input type="hidden" id="lat" name="latitude"--}}
                {{--                                   class="form-control mb-2"--}}
                {{--                                   readonly="yes" required="required"/>--}}

                {{--                            @if ($errors->has('latitude'))--}}

                {{--                                <span class="help-block">--}}

                {{--                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>--}}

                {{--                        </span>--}}

                {{--                            @endif--}}

                {{--                            <input type="hidden" id="lng" name="longitude"--}}
                {{--                                   class="form-control mb-2"--}}
                {{--                                   readonly="yes" required="required"/>--}}

                {{--                            @if ($errors->has('longitude'))--}}

                {{--                                <span class="help-block">--}}

                {{--                            <strong style="color: red;">{{ $errors->first('longitude') }}</strong>--}}

                {{--                        </span>--}}

                {{--                            @endif--}}

                {{--                            <div id="map"></div>--}}

                {{--                        </div>--}}

                {{--                    </div>--}}

                <div class="input-group mb-3">

                    <input type="checkbox" name="terms" required>
                    @lang('messages.terms_and_conditions')

                </div>
                <div class="row">
                    <div class="col-4">
                        <button type="submit" id="send_form"
                                class="btn btn-primary btn-block"> @lang('messages.confirm') </button>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <a target="_blank"   href="https://api.whatsapp.com/send?phone=966590136653" style="color: green">
                            <i style="font-size:24px" class="fa">&#xf232;</i>
                            <span class="hidemob">
                                    @lang('messages.technical_support')
                                </span>
                        </a>
                    </div>
                </div>
            </form>


        </div>

    </div>
</div>
<script>
    if ($("#post-form").length > 0) {
        $("#post-form").validate({

            rules: {
                city_id: {
                    required: true,
                },
                package_id: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                latitude: {
                    required: true,
                },
                longitude: {
                    required: true,
                },
                seller_code: {
                    required: false,
                    maxlength: 191
                },
                name_barcode: {
                    required: true,
                    maxlength: 191
                },
                terms: {
                    required: true,
                },

            },
            messages: {
                city_id: {
                    required: "{{trans('messages.city')}}" + " " + "{{trans('messages.required')}}",
                },
                package_id: {
                    required: "{{trans('messages.package')}}" + " " + "{{trans('messages.required')}}",
                },
                category_id: {
                    required: "{{trans('messages.MCategory')}}" + " " + "{{trans('messages.required')}}",
                },
                latitude: {
                    required: "{{trans('messages.latitude')}}" + " " + "{{trans('messages.required')}}",
                },
                terms: {
                    required: "{{trans('messages.terms_and_conditions')}}" + " " + "{{trans('messages.required')}}",
                },
                seller_code: {
                    required: "{{trans('messages.seller_code')}}" +" "+ "{{trans('messages.required')}}",
                    maxlength: "{{trans('messages.max_length')}}" + " "+ "{{trans('messages.seller_code')}}" + "191",
                },
                name_barcode: {
                    required: "{{trans('messages.name_barcode')}}" +" "+ "{{trans('messages.required')}}",
                    maxlength: "{{trans('messages.max_length')}}" + " "+ "{{trans('messages.name_barcode')}}" + "191",
                },

            },
            submitHandler: function (form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#send_form').html('Sending..');
                $.ajax({
                    url: "{{ url('/') }}" + "/restaurant/store/step2/" + "{{$restaurant->id}}",
                    type: "POST",
                    data: $('#post-form').serialize(),
                    success: function (response) {
                        if (response.errors && response.errors.length > 0) {
                            jQuery.each(response.errors, function (key, value) {
                                jQuery('.alert-danger').show();
                                jQuery('.alert-danger').append('<p>' + value + '</p>');
                            });
                        } else {
                            $('#send_form').html('Submit');
                            $('#res_message').show();
                            $('#res_message').html(response.msg);
                            $('#msg_div').removeClass('d-none');

                            document.getElementById("post-form").reset();
                            setTimeout(function () {
                                $('#res_message').hide();
                                $('#msg_div').hide();
                            }, 10000);
                            window.location = response.url;
                        }
                    }
                });
            }
        })
    }
</script>

<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    })
</script>


