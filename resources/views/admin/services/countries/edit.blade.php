@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.service_countries')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('dashboard.service_countries') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.service.country.index' , $service->id)}}">
                                @lang('dashboard.service_countries')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.service_countries') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('admin.service.country.update' , [$service->id , $serviceCountry->id])}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            @method('put')
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.country') </label>
                                    <select name="country_id" class="form-control" required>
                                        <option disabled selected>
                                            @lang('messages.choose_one')
                                        </option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" {{$serviceCountry->country_id == $country->id ? 'selected' : ''}}>
                                                {{app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en}}
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
                                    <label class="control-label"> @lang('messages.price') </label>
                                    <input name="price" type="number" class="form-control" value="{{$serviceCountry->price}}" placeholder="@lang('messages.price')">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
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
