@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.restaurants')
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.restaurants') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurants' , 'active')}}">
                                @lang('messages.restaurants')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.restaurants') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('storeRestaurant')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.categories') </label>
                                    <select class="select2-multiple form-control" name="category_id[]" multiple="multiple"
                                            id="select2Multiple">
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

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.country') </label>
                                    <select name="country_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$country->name_ar}}
                                                @else
                                                    {{$country->name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('country_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.city') </label>
                                    <select id="register_city" name="city_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>

                                    </select>
                                    @if ($errors->has('city_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('city_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_ar') </label>
                                    <input name="name_ar" type="text" class="form-control" value="{{old('name_ar')}}"
                                           placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('name_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_en') </label>
                                    <input name="name_en" type="text" class="form-control" value="{{old('name_en')}}"
                                           placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_barcode_rules') </label>
                                    <input type="text" name="name_barcode"  class="form-control" value="{{old('name_barcode')}}" placeholder="@lang('messages.name_barcode')">
                                    @if ($errors->has('name_barcode'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_barcode') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.email') </label>
                                    <input name="email" type="email" class="form-control"
                                           value="{{old('email')}}"
                                           placeholder="@lang('messages.email')">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.phone_number') </label>
                                    <input name="phone_number" type="number" class="form-control"
                                           value="{{old('phone_number')}}"
                                           placeholder="@lang('messages.phone_number')">
                                    @if ($errors->has('phone_number'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.password') </label>
                                    <input name="password" type="password" class="form-control"
                                           value="{{old('password')}}"
                                           placeholder="@lang('messages.password')">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.password_confirmation') </label>
                                    <input name="password_confirmation" type="password" class="form-control"
                                           value="{{old('password_confirmation')}}"
                                           placeholder="@lang('messages.password_confirmation')">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.end_at') </label>
                                    <input name="end_at" type="date" class="form-control" value="{{old('end_at')}}"
                                           placeholder="@lang('messages.end_at')">
                                    @if ($errors->has('end_at'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{--                                <div class="form-group">--}}
                                {{--                                    --}}{{--                                    <label class="control-label"> @lang('messages.password_confirmation') </label>--}}
                                {{--                                    <h4 style="text-align: right">  @lang('messages.selectPosition')  </h4>--}}
                                {{--                                    <input type="text" id="lat" name="latitude" readonly="yes" required/>--}}
                                {{--                                    <input type="text" id="lng" name="longitude" readonly="yes" required/>--}}
                                {{--                                    <a class="btn btn-info" onclick="getLocation()"> @lang('messages.MyPosition') </a>--}}
                                {{--                                    <hr>--}}
                                {{--                                    <div id="map" style="position: relative; height: 600px; width: 600px; "></div>--}}
                                {{--                                </div>--}}

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Select",
                allowClear: true
            });

        });

    </script>
    <script>
        $(document).ready(function() {
            $('select[name="country_id"]').on('change', function() {
                var id = $(this).val();
                $.ajax({
                    url: '/get/cities/'+id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                        $('#register_city').empty();
                        // $('select[name="city_id"]').append("<option disabled selected> choose </option>");
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $('select[name="city_id"]').append("<option disabled selected> @lang('messages.choose_one') </option>");
                        $.each(data, function(index , cities) {
                            console.log(cities);
                            @if(app()->getLocale() == 'ar')
                            $('select[name="city_id"]').append('<option value="'+ cities.id +'">'+ cities.name_ar+'</option>');
                            @else
                            $('select[name="city_id"]').append('<option value="'+ cities.id +'">'+ cities.name_en+'</option>');
                            @endif
                        });
                    }
                });
            });
        });
    </script>

    
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
