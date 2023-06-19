@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.country_packages')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.country_packages') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('country_packages.index' , $country->id)}}">
                                @lang('messages.country_packages')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.country_packages') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('country_packages.update' , $country_package->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
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
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.price') </label>
                                    <input name="price" type="number" class="form-control" value="{{$country_package->price}}" placeholder="@lang('messages.price')">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch_price') </label>
                                    <input name="branch_price" type="number" class="form-control" value="{{$country_package->branch_price}}" placeholder="@lang('messages.branch_price')">
                                    @if ($errors->has('branch_price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_price') }}</strong>
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
