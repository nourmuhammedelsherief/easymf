@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.the_branches')
@endsection

@section('styles')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.the_branches') </h1>
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.the_branches') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('branches.update' , $branch->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.country') </label>
                                    <select name="country_id" class="form-control" disabled required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($countries as $country)
                                            <option
                                                value="{{$country->id}}" {{$branch->country_id == $country->id ? 'selected' : ''}}>
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
                                        <option disabled> @lang('messages.choose_one') </option>
                                        <option value="{{$branch->city_id}}"
                                                selected> {{app()->getLocale() == 'ar' ? $branch->city->name_ar : $branch->city->name_en}} </option>
                                    </select>
                                    @if ($errors->has('city_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('city_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_ar') </label>
                                        <input name="name_ar" type="text" class="form-control"
                                               value="{{$branch->name_ar}}" placeholder="@lang('messages.name_ar')">
                                        @if ($errors->has('name_ar'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_en') </label>
                                    <input name="name_en" type="text" class="form-control" required
                                           value="{{$branch->name_en}}" placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_barcode_branch') </label>
                                    <input name="name_barcode" type="text" class="form-control"
                                           value="{{$branch->name_barcode}}" disabled
                                           placeholder="@lang('messages.name_barcode_branch')">
                                    <h6 style="color: red">@lang('messages.whenChangeNameBranch')</h6>
                                    @if ($errors->has('name_barcode'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_barcode') }}</strong>
                                        </span>
                                    @endif
                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                                    <a target="_blank" href="https://api.whatsapp.com/send?phone=966590136653"
                                       style="color: green">
                                        <i style="font-size:24px" class="fa">&#xf232;</i>
                                        <span class="hidemob">
                                                    @lang('messages.technical_support')
                                                </span>
                                    </a>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number"
                                           class="col-sm-3 control-label">@lang('messages.tax_activation')</label>

                                    <div class="col-sm-9">
                                        <input type="radio" id="noCheck" onclick="javascript:yesnoCheck();"
                                               name="tax"
                                               value="true" {{$branch->tax == 'true' ? 'checked' : ''}}> @lang('messages.yes')
                                        <input type="radio" onclick="javascript:yesnoCheck();" id="yesCheck"
                                               name="tax"
                                               value="false" {{$branch->tax == 'false' ? 'checked' : ''}}> @lang('messages.no')
                                    </div>
                                    @if ($errors->has('tax'))
                                        <div class="alert alert-danger">
                                            <button class="close" data-close="alert"></button>
                                            <span> {{ $errors->first('tax') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div id="ifYes" style="display: {{$branch->tax == 'false' ? 'none' : 'block'}}">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="name_en"
                                                   class="col-sm-3 control-label">@lang('messages.tax_value')</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control" name="tax_value"
                                                       value="{{$branch->tax_value}}" id="tax_value"
                                                       placeholder="@lang('messages.tax_value')">
                                            </div>
                                            <div class="col-sm-1">%</div>
                                        </div>
                                        {{--                                            <h6 style="color: red">@lang('messages.whenChangeName')</h6>--}}
                                        @if ($errors->has('tax_value'))
                                            <div class="alert alert-danger">
                                                <button class="close" data-close="alert"></button>
                                                <span> {{ $errors->first('tax_value') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="name_en"
                                                   class="col-sm-3 control-label">
                                                {{app()->getLocale() == 'ar' ? 'الرقم الضريبي': 'tax number'}}
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="tax_number"
                                                       value="{{$branch->tax_number}}" id="tax_number"
                                                       placeholder="{{app()->getLocale() == 'ar' ? 'الرقم الضريبي': 'tax number'}}">
                                            </div>
                                        </div>
                                        {{--                                            <h6 style="color: red">@lang('messages.whenChangeName')</h6>--}}
                                        @if ($errors->has('tax_number'))
                                            <div class="alert alert-danger">
                                                <button class="close" data-close="alert"></button>
                                                <span> {{ $errors->first('tax_number') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number"
                                           class="col-sm-3 control-label">@lang('messages.total_tax_price')</label>

                                    <div class="col-sm-9">
                                        <input type="radio" name="total_tax_price"
                                               value="true" {{$branch->total_tax_price == 'true' ? 'checked' : ''}}> @lang('messages.yes')
                                        <input type="radio" name="total_tax_price"
                                               value="false" {{$branch->total_tax_price == 'false' ? 'checked' : ''}}> @lang('messages.no')
                                    </div>
                                    @if ($errors->has('total_tax_price'))
                                        <div class="alert alert-danger">
                                            <button class="close" data-close="alert"></button>
                                            <span> {{ $errors->first('total_tax_price') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="password"
                                           class="col-sm-3 control-label">
                                        {{app()->getLocale() == 'ar' ? 'حالة الفرع' : 'Branch Status'}}
                                    </label>

                                    <div class="col-sm-9">
                                        {{--                                                <input type="radio" name="state"--}}
                                        {{--                                                       value="open" {{$branch->state == 'open' ? 'checked' : ''}}> @lang('messages.open')--}}
                                        <input type="radio" name="state"
                                               value="closed" {{$branch->state == 'closed' ? 'checked' : ''}}> @lang('messages.closed')
                                        <input type="radio" name="state"
                                               value="busy" {{$branch->state == 'busy' ? 'checked' : ''}}> @lang('messages.busy')
                                        <input type="radio" name="state"
                                               value="unspecified" {{$branch->state == 'unspecified' ? 'checked' : ''}}> @lang('messages.un_available')
                                    </div>
                                    @if ($errors->has('state'))
                                        <div class="alert alert-danger">
                                            <button class="close" data-close="alert"></button>
                                            <span> {{ $errors->first('state') }}</span>
                                        </div>
                                    @endif
                                </div>


                                {{--                                <div class="form-group">--}}
                                {{--                                    <h4 style="text-align: right">  @lang('messages.selectPosition')  </h4>--}}
                                {{--                                    <input type="text" id="lat" name="latitude" value="{{$branch->latitude}}"--}}
                                {{--                                           readonly="yes" required>--}}
                                {{--                                    <input type="text" id="lng" name="longitude" value="{{$branch->longitude}}"--}}
                                {{--                                           readonly="yes" required>--}}
                                {{--                                    <a class="btn btn-info" onclick="getLocation()"> @lang('messages.MyPosition') </a>--}}
                                {{--                                    <hr>--}}
                                {{--                                    <div id="map" style="position: relative; height: 600px; width: 600px; "></div>--}}
                                {{--                                </div>--}}

                            </div>
                            <!-- /.card-body -->
                            @method('PUT')
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
    <script type="text/javascript">

        function yesnoCheck() {
            if (document.getElementById('yesCheck').checked) {
                document.getElementById('ifYes').style.display = 'none';
            } else {
                document.getElementById('ifYes').style.display = 'block';
            }
        }
    </script>

@endsection
