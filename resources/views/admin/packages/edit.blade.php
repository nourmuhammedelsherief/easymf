@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.packages')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.packages') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('packages.index')}}">
                                @lang('messages.packages')
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
                <div class="col-md-9">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.packages') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('packages.update' , $package->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_ar') </label>
                                    <input name="name_ar" type="text" class="form-control" value="{{$package->name_ar}}" placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('name_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_en') </label>
                                    <input name="name_en" type="text" class="form-control" value="{{$package->name_en}}" placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- <div class="form-group">
                                    <label class="control-label"> @lang('messages.discounted_price') </label>
                                    <input name="discounted_price" type="number" class="form-control" value="{{$package->discounted_price}}" placeholder="@lang('messages.discounted_price')">
                                    @if ($errors->has('discounted_price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('discounted_price') }}</strong>
                                        </span>
                                    @endif
                                </div> --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.package_price') </label>
                                    <input name="price" type="text" class="form-control" value="{{$package->price}}" placeholder="@lang('messages.package_price')">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch_price_before') </label>
                                    <input name="branch_price_before" type="number" class="form-control" value="{{$package->branch_price_before}}" placeholder="@lang('messages.branch_price_before')">
                                    @if ($errors->has('branch_price_before'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_price_before') }}</strong>
                                        </span>
                                    @endif
                                </div> --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch_price') </label>
                                    <input name="branch_price" type="text" class="form-control" value="{{$package->branch_price}}" placeholder="@lang('messages.branch_price')">
                                    @if ($errors->has('branch_price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.package_duration') </label>
                                    <input name="duration" type="number" class="form-control" value="{{$package->duration}}" placeholder="@lang('messages.package_duration')">
                                    @if ($errors->has('duration'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('duration') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.description_ar') </label>
                                    <textarea class="textarea" name="description_ar" placeholder="@lang('messages.description_ar')"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$package->description_ar}}</textarea>
                                    @if ($errors->has('description_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('description_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.description_en') </label>
                                    <textarea class="textarea" name="description_en" placeholder="@lang('messages.description_en')"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$package->description_en}}</textarea>
                                    @if ($errors->has('description_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('description_en') }}</strong>
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
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
