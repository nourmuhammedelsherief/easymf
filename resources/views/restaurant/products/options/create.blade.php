@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.options')
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}">
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.options')  ({{app()->getLocale() == 'ar' ? $product->name_ar : $product->name_en}})</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('productOption' , $product->id)}}">
                                @lang('messages.options')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.options') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('storeProductOption' , $product->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.options') </label>
                                    <select name="option_id[]" class="select2 form-control" multiple required>
                                        {{-- <option disabled selected> @lang('messages.choose_one') </option> --}}
                                        @foreach($options as $option)
                                            <option value="{{$option->id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$option->name_ar == null ? $option->name_en : $option->name_ar }}  ({{$option->modifier->name_ar == null ? $option->modifier->name_en : $option->modifier->name_ar}})
                                                @else
                                                    {{$option->name_en == null ? $option->name_ar : $option->name_en}}    ({{$option->modifier->name_en == null ? $option->modifier->name_ar : $option->modifier->name_en}})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('option_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('option_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.min') </label>
                                    <input name="min" type="number" class="form-control" value="{{old('min')}}" placeholder="@lang('messages.min')">
                                    @if ($errors->has('min'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('min') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.max') </label>
                                    <input name="max" type="number" class="form-control" value="{{old('max')}}" placeholder="@lang('messages.max')">
                                    @if ($errors->has('max'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('max') }}</strong>
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

@section('scripts')
    <script src="{{asset('plugins/select2/js/select2.js')}}"></script>
    <script>
        $(function(){
            $('.select2').select2({
                language: "{{app()->getLocale()}}" , 
                dir : "{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}"
            });
        });
    </script>
@endsection