@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.previous_periods')
@endsection
@section('style')
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.previous_periods') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant_setting.index')}}">
                                @lang('messages.previous_periods')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.previous_periods') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('order_setting_days.store' , $setting->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.start_at') </label>
                                    <input name="start_at" type="time" class="form-control"
                                           value="{{old('start_at')}}" placeholder="@lang('messages.start_at')">
                                    @if ($errors->has('start_at'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('start_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.end_at') </label>
                                    <input name="end_at" type="time" class="form-control"
                                           value="{{old('end_at')}}" placeholder="@lang('messages.end_at')">
                                    @if ($errors->has('end_at'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.days') </label>
                                    <br>
                                    <input id="select-all" type="checkbox"><label for='select-all'> {{app()->getLocale() == 'ar' ? 'اختيار الكل':'Choose All' }}</label>
                                    <br>

                                    <?php $days = \App\Models\Day::all(); ?>
                                    @foreach($days as $day)
                                        <input type="checkbox" name="day_id[]" value="{{$day->id}}">
                                        {{app()->getLocale() == 'ar' ? $day->name_ar : $day->name_en}}
                                    @endforeach
                                    @if ($errors->has('day_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('day_id') }}</strong>
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
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

    <script>
        $("#select-all").click(function(){
            $("input[type=checkbox]").prop('checked',$(this).prop('checked'));
        });
    </script>
@endsection
