@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.our_services')
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
                    <h1> @lang('messages.edit') @lang('dashboard.our_services') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.service.index' , $service->id)}}">
                                @lang('dashboard.our_services')
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
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.our_services') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('admin.service.update' , $service->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            <input type="hidden" name="_method" value="PUT">
                            <div class="card-body">
                                
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name') </label>
                                    <input name="name" type="text" class="form-control" value="{{$service->name}}" placeholder="@lang('messages.name')">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.price') </label>
                                    <input name="price" type="text" step=".1" class="form-control" value="{{$service->price}}" placeholder="@lang('messages.price')">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                {{-- categories --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.category') </label>
                                    <select name="category_id" class="form-control">
                                        <option value="" disabled selected>اختر </option>
                                        @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$service->category_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.status') </label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true" {{$service->status == 'true' ? 'selected' : ''}}>{{ trans('dashboard.yes') }}</option>
                                        <option value="false" {{$service->status == 'false' ? 'selected' : ''}}>{{ trans('dashboard.no') }}</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            
                                {{-- description_ar --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.description_ar') </label>
                                    <textarea class="form-control textarea" name="description_ar"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$service->description_ar}}</textarea>
                                    @if ($errors->has('description_ar'))
                                        <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('description_ar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                 {{-- description_en --}}
                                 <div class="form-group">
                                    <label class="control-label"> @lang('messages.description_en') </label>
                                    <textarea class="form-control textarea" name="description_en"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$service->description_en}}</textarea>
                                    @if ($errors->has('description_en'))
                                        <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('description_en') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                {{-- image --}}
                                <div class="form-group ">
                                    <label class="control-label col-md-3"> @lang('messages.photo') </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 200px; height: 150px; border: 1px solid black;">
                                                 @if($service->photo != null)
                                                    <img src="{{asset($service->image_path)}}">
                                                @endif
                                            </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span
                                                        class="fileinput-new btn btn-info"> @lang('messages.choose_photo') </span>
                                                    <span
                                                        class="fileinput-exists btn btn-primary"> @lang('messages.change') </span>
                                                    <input type="file" name="photo"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>
                                            </div>
                                        </div>
                                        @if ($errors->has('photo'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('photo') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                            {{--                            @method('PUT')--}}
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
