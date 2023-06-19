@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.cities')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @lang('messages.add') @lang('messages.cities')
                        ({{app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en}})
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('cities.index' , $country->id)}}">
                                @lang('messages.cities')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.cities') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('cities.store' , $country->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
{{--                                <div class="form-group">--}}
{{--                                    <label class="control-label"> @lang('messages.country') </label>--}}
{{--                                    <select name="country_id" class="form-control" required>--}}
{{--                                        <option disabled selected> @lang('messages.choose_one') </option>--}}
{{--                                        @foreach($countries as $country)--}}
{{--                                            <option value="{{$country->id}}">--}}
{{--                                                @if(app()->getLocale() == 'ar')--}}
{{--                                                    {{$country->name_ar}}--}}
{{--                                                @else--}}
{{--                                                    {{$country->name_en}}--}}
{{--                                                @endif--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    @if ($errors->has('country_id'))--}}
{{--                                        <span class="help-block">--}}
{{--                                                       <strong style="color: red;">{{ $errors->first('country_id') }}</strong>--}}
{{--                                                    </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_ar') </label>
                                    <input name="name_ar" type="text" class="form-control" value="{{old('name_ar')}}" placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('name_ar'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name_en') </label>
                                    <input name="name_en" type="text" class="form-control" value="{{old('name_en')}}" placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
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
