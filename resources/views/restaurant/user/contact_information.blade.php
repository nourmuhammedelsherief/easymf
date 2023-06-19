@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.my_information')
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
                    <h1> @lang('messages.edit') @lang('messages.my_information') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('information')}}">
                                @lang('messages.my_information')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.my_information') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{route('RestaurantUpdateInformation')}}" class="form-horizontal mt-4"
                                method="post" enctype="multipart/form-data">
                            @csrf
                            {{-- is call phone --}}
                            <div class="form-group">
                                <label for="phone_number"
                                        class="col-sm-3 control-label">@lang('dashboard.entry.call_phone')</label>

                                <div class="col-sm-9">
                                    <input type="radio" class="call-phone" name="is_call_phone"
                                            value="true" {{$user->is_call_phone == 'true' ? 'checked' : ''}}> @lang('messages.yes')
                                    <input type="radio" class="call-phone" name="is_call_phone"
                                            value="false" {{$user->is_call_phone == 'false' ? 'checked' : ''}}> @lang('messages.no')
                                </div>
                                @if ($errors->has('is_call_phone'))
                                    <div class="alert alert-danger">
                                        <button class="close" data-close="alert"></button>
                                        <span> {{ $errors->first('is_call_phone') }}</span>
                                    </div>
                                @endif
                            </div>
                            {{-- call phone number --}}
                            <div class="form-group callphone {{$user->is_call_phone == 'false' ? 'display-none' : '' }}">
                                <label for="phone_number"
                                        class="col-sm-3 control-label">@lang('dashboard.entry.call_phone_')</label>

                                <div class="col-sm-9">
                                    <input type="text" name="call_phone" class="form-control" placeholder="+966xxxxxxxxx" value="{{$user->call_phone}}">
                                </div>
                                @if ($errors->has('call_phone'))
                                    <div class="alert alert-danger">
                                        <button class="close" data-close="alert"></button>
                                        <span> {{ $errors->first('call_phone') }}</span>
                                    </div>
                                @endif
                            </div>
                            {{-- is whatsapp --}}
                            <div class="form-group">
                                <label for="phone_number"
                                        class="col-sm-3 control-label">@lang('dashboard.entry.is_whatsapp')</label>

                                <div class="col-sm-9">
                                    <input type="radio" class="call-phone" name="is_whatsapp"
                                            value="true" {{$user->is_whatsapp == 'true' ? 'checked' : ''}}> @lang('messages.yes')
                                    <input type="radio" class="call-phone" name="is_whatsapp"
                                            value="false" {{$user->is_whatsapp == 'false' ? 'checked' : ''}}> @lang('messages.no')
                                </div>
                                @if ($errors->has('is_whatsapp'))
                                    <div class="alert alert-danger">
                                        <button class="close" data-close="alert"></button>
                                        <span> {{ $errors->first('is_whatsapp') }}</span>
                                    </div>
                                @endif
                            </div>
                            {{-- whatsapp number --}}
                            <div class="form-group whatsapp {{$user->is_whatsapp == 'false' ? 'display-none' : '' }}">
                                <label for="phone_number"
                                        class="col-sm-3 control-label">@lang('dashboard.entry.whatsapp_number')</label>

                                <div class="col-sm-9">
                                    <input type="text" name="whatsapp_number" placeholder="+966xxxxxxxxx" class="form-control" value="{{$user->whatsapp_number}}">
                                </div>
                                @if ($errors->has('whatsapp_number'))
                                    <div class="alert alert-danger">
                                        <button class="close" data-close="alert"></button>
                                        <span> {{ $errors->first('whatsapp_number') }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit"
                                            class="btn btn-danger">@lang('messages.save')</button>
                                </div>
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
        $(function(){
            $('input[name=is_call_phone]').on('change' , function(){
                console.log('check : ' + $(this).val());
                if($(this).val() == 'true')
                $('.form-group.callphone').fadeIn(100);
                else 
                $('.form-group.callphone').fadeOut(100);
            });
            $('input[name=is_whatsapp]').on('change' , function(){
                console.log('check : ' + $(this).val());
                if($(this).val() == 'true')
                $('.form-group.whatsapp').fadeIn(100);
                else 
                $('.form-group.whatsapp').fadeOut(100);
            });
        }); 
    </script>
@endsection
