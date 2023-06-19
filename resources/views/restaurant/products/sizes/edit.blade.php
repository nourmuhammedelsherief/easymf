@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.sizes')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.sizes')
                        ({{app()->getLocale() == 'ar' ? $size->product->name_ar : $size->product->name_en}})</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('productSize' , $size->product->id)}}">
                                @lang('messages.sizes')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.sizes') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('updateProductSize' , $size->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_ar') </label>
                                        <input name="name_ar" type="text" class="form-control"
                                               value="{{$size->name_ar}}"
                                               placeholder="@lang('messages.name_ar')">
                                        @if ($errors->has('name_ar'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                @if(Auth::guard('restaurant')->user()->en == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_en') </label>
                                        <input name="name_en" type="text" class="form-control"
                                               value="{{$size->name_en}}"
                                               placeholder="@lang('messages.name_en')">
                                        @if ($errors->has('name_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.price') </label>
                                    <input name="price" type="number" class="form-control" value="{{$size->price}}"
                                           placeholder="@lang('messages.price')">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.calories') </label>
                                    <input name="calories" type="number" class="form-control"
                                           value="{{$size->calories}}" placeholder="@lang('messages.calories')">
                                    @if ($errors->has('calories'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('calories') }}</strong>
                                        </span>
                                    @endif
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
