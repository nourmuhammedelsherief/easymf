@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.restaurants')
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.restaurants') </h1>
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.restaurants') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{(isset($inComplete) and $inComplete == true) ? route('inCompleteRestaurant' , $restaurant->id) : route('updateRestaurant' , $restaurant->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                @if($tempSeller = $restaurant->marketerOperations()->with('seller_code' , 'subscription')->first() and isset($tempSeller->seller_code->id) and $tempSeller->seller_code->used_type == 'url')
                                    <div class="form-group text-left">
                                        <a href="{{url('admin/seller_codes/' . $tempSeller->seller_code_id)}}?custom_url={{$tempSeller->seller_code->custom_url}}">تسجيل مباشر : {{$tempSeller->seller_code->custom_url}}</a>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.country') </label>
                                    <select name="country_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" {{$restaurant->country_id == $country->id ? 'selected' : ''}}>
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
                                @if($restaurant->city != null)
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.city') </label>
                                        <select id="register_city" name="city_id" class="form-control" required>
                                            <option disabled selected> @lang('messages.choose_one') </option>
                                            @foreach ($countries as $country)
                                                @if($country->id == $restaurant->country_id)
                                                    @foreach ($country->cities as $city)
                                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                            <option value="{{$restaurant->city_id}}"
                                                    selected> {{app()->getLocale() == 'ar' ? $restaurant->city->name_ar : $restaurant->city->name_en}}
                                            </option>
                                        </select>
                                        @if ($errors->has('city_id'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('city_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.city') </label>
                                        <select id="register_city" name="city_id" class="form-control" required>
                                            <option disabled selected> @lang('messages.choose_one') </option>
                                            {{--                                            <option value="{{$restaurant->city_id}}"--}}
                                            {{--                                                    selected> {{app()->getLocale() == 'ar' ? $restaurant->city->name_ar : $restaurant->city->name_en}}--}}
                                            {{--                                            </option>--}}
                                        </select>
                                        @if ($errors->has('city_id'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('city_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_ar') </label>
                                    <input name="name_ar" type="text" class="form-control" value="{{$restaurant->name_ar}}"
                                           placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('name_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_en') </label>
                                    <input name="name_en" type="text" class="form-control" value="{{$restaurant->name_en}}"
                                           placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_barcode_rules') </label>
                                    <input type="text" name="name_barcode"  class="form-control" value="{{$restaurant->name_barcode}}" placeholder="@lang('messages.name_barcode')">
                                    @if ($errors->has('name_barcode'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_barcode') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.email') </label>
                                    <input name="email" type="email" class="form-control"
                                           value="{{$restaurant->email}}"
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
                                           value="{{$restaurant->phone_number}}"
                                           placeholder="@lang('messages.phone_number')">
                                    @if ($errors->has('phone_number'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if($restaurant->status != 'inComplete')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.end_at') </label>
                                        <input name="end_at" type="date" class="form-control"
                                               value="{{$restaurant->subscription->end_at->format('Y-m-d')}}"
                                               placeholder="@lang('messages.end_at')">
                                        @if ($errors->has('end_at'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_at') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
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
            // $('select[name=country_id]').trigger('change');
            $(document).on('submit', 'form', function () {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
