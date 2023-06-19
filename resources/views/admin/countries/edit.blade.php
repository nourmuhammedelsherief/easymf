@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.countries')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.countries') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('countries.index')}}">
                                @lang('messages.countries')
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
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.countries') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('countries.update' , $country->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_ar') </label>
                                    <input name="name_ar" type="text" class="form-control" value="{{$country->name_ar}}" placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('name_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_en') </label>
                                    <input name="name_en" type="text" class="form-control" value="{{$country->name_en}}" placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.currency_ar') </label>
                                    <input name="currency_ar" type="text" class="form-control" value="{{$country->currency_ar}}" placeholder="@lang('messages.currency_ar')">
                                    @if ($errors->has('currency_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('currency_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.currency_en') </label>
                                    <input name="currency_en" type="text" class="form-control" value="{{$country->currency_en}}" placeholder="@lang('messages.currency_en')">
                                    @if ($errors->has('currency_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('currency_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.country_code') </label>
                                    <input name="code" type="text" class="form-control" value="{{$country->code}}" placeholder="@lang('messages.country_code')">
                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-md-3"> علم الدولة </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 200px; height: 150px; border: 1px solid black;">
                                                @if($country->flag != null)
                                                    <img src="{{asset('/uploads/flags/' . $country->flag)}}">
                                                @endif
                                            </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span
                                                        class="fileinput-new btn btn-info"> @lang('messages.choose_photo') </span>
                                                    <span
                                                        class="fileinput-exists btn btn-primary"> @lang('messages.change') </span>
                                                    <input type="file" name="flag"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>
                                            </div>
                                        </div>
                                        @if ($errors->has('poster'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('poster') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                {{-- currency --}}
                                {{-- <div class="form-group">
                                    <label class="control-label"> @lang('messages.packages') </label>
                                    <select name="package_id" class="form-control" required>
                                        <option disabled selected>
                                            @lang('messages.choose_one')
                                        </option>
                                        @foreach($packages as $package)
                                            <option value="{{$package->id}}" {{$country_package->package_id == $package->id ? 'selected' : ''}}>
                                                {{app()->getLocale() == 'ar' ? $package->name_ar : $package->name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('package_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('package_id') }}</strong>
                                        </span>
                                    @endif
                                </div> --}}
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
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
@endsection
