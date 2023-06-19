@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.socials')
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
                    <h1> @lang('messages.edit') @lang('messages.socials') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('socials.index')}}">
                                @lang('messages.socials')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.socials') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('socials.update' , $social->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_ar') </label>
                                        <input name="name_ar" type="text" class="form-control"
                                               value="{{$social->name_ar}}" placeholder="@lang('messages.name_ar')">
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
                                               value="{{$social->name_en}}" placeholder="@lang('messages.name_en')">
                                        @if ($errors->has('name_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.link') </label>
                                    <input name="link" type="url" class="form-control" value="{{$social->link}}"
                                           placeholder="@lang('messages.link')">
                                    @if ($errors->has('link'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('link') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-md-3"> @lang('messages.icon') </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 200px; height: 150px; border: 1px solid black;">
                                                @if($social->icon != null)
                                                    <img src="{{asset('/uploads/socials/' . $social->icon)}}">
                                                @endif
                                            </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span
                                                        class="fileinput-new btn btn-info"> @lang('messages.choose_photo') </span>
                                                    <span
                                                        class="fileinput-exists btn btn-primary"> @lang('messages.change') </span>
                                                    <input type="file" name="icon"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>
                                            </div>
                                        </div>
                                        @if ($errors->has('icon'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('icon') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>


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
