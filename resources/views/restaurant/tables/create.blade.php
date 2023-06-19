@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.tables')
@endsection

@section('styles')
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.tables') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('tables.index')}}">
                                @lang('messages.tables')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.tables') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('tables.store')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                {{--                                @if($service_id != null)--}}
                                {{--                                    <input type="hidden" value="{{null}}" name="branch_id">--}}
                                <input type="hidden" value="{{$service_id == null ? null : $service_id}}" name="service_id">
                                {{--                                @else--}}
                                {{--                                    --}}
                                {{--                                @endif--}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch') </label>
                                    <select name="branch_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$branch->name_ar}}
                                                @else
                                                    {{$branch->name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_ar') </label>
                                        <input name="name_ar" type="text" class="form-control"
                                               value="{{old('name_ar')}}" placeholder="@lang('messages.name_ar')">
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
                                               value="{{old('name_en')}}" placeholder="@lang('messages.name_en')">
                                        @if ($errors->has('name_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.table_name_barcode') </label>
                                    <input name="name_barcode" type="text" class="form-control"
                                           value="{{old('name_barcode')}}"
                                           placeholder="@lang('messages.table_name_barcode')">
                                    @if ($errors->has('name_barcode'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_barcode') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.table_code') </label>
                                    <input name="code" type="number" class="form-control" value="{{old('code')}}"
                                           placeholder="@lang('messages.table_code')">
                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('code') }}</strong>
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
