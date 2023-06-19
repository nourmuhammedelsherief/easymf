@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.ads')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('dashboard.ads') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('adminAds.index')}}">
                                @lang('dashboard.ads')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.ads') </h3>
                        </div>
                        <!-- /.card-header -->
                        @foreach ($errors->all() as $item)
                            <p class="text-danger">{{$item}}</p>
                        @endforeach
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('adminAds.update' , $ads->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type="hidden" name="type" value="{{$ads->type}}">
                            <input type="hidden" name="content_type" value="{{$ads->content_type}}">
                            
                            <input type="hidden" name="_method" value="PUT">
                            

                            <div class="card-body">
                              
                                     {{-- start_date --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.start_date') </label>
                                    <input type="date" name="start_date" class="form-control" value="{{$ads->start_date}}">
                                    @if ($errors->has('start_date'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('start_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- end_date --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.end_date') </label>
                                    <input type="date" name="end_date" class="form-control" value="{{$ads->end_date}}">
                                    @if ($errors->has('end_date'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.type') </label>
                                    <select name="type" id="" class="form-control select2" required>
                                        <option value="" disabled selected>{{ trans('dashboard.choose') }}</option>
                                        @foreach (trans('dashboard._ads_type') as $key => $item)
                                             <option value="{{$key}}" {{$ads->type == $key ? 'selected' : ''}}>{{trans('dashboard._ads_type.' . $key)}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- content_type --}}
                                <div class="form-group" style="display: block">
                                    <label class="control-label"> @lang('dashboard.entry.content_type') </label>
                                    <select name="content_type" id="" class="form-control " >
                                        <option value="">{{ trans('dashboard.choose') }}</option>
                                        <option value="image" {{$ads->content_type == 'image' ? 'selected' : ''}}>{{ trans('dashboard.image') }}</option>
                                        <option value="youtube" {{$ads->content_type == 'youtube' ? 'selected' : ''}}>{{ trans('dashboard.youtube') }}</option>
                                    </select>
                                    @if ($errors->has('content_type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('content_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- url --}}
                                <div class="form-group content-link">
                                    <label class="control-label"> @lang('dashboard.youtube') </label>
                                    <input type="text" name="link" class="form-control" value="{{$ads->content_type == 'youtube' ? $videoId : ''}}" placeholder="مثال : xxxxxxx">
                                    <p class="text-mute">{{ trans('dashboard.youtube_link_code') }}</p>
                                    @if ($errors->has('link'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('link') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- time --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.time_activation') </label>
                                    <input name="time" type="radio" onclick="javascript:yesnoCheck();" value="true"
                                            {{$ads->time == 'true' ? 'checked' : ''}} placeholder="@lang('messages.time')" id="noCheck"> @lang('messages.yes')
                                    <input name="time" onclick="javascript:yesnoCheck();" type="radio" value="false" {{$ads->time == 'false' ? 'checked' : ''}} 
                                           placeholder="@lang('messages.time')" id="yesCheck"> @lang('messages.no')
                                    @if ($errors->has('time'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('time') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div id="ifYes" style="display:{{$ads->time == "true" ? 'block' : 'none'}}">
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.start_at') </label>
                                            <input name="start_at" type="time" class="form-control"
                                                   value="{{$ads->start_at}}"
                                                   placeholder="@lang('messages.start_at')">
                                            @if ($errors->has('start_at'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('start_at') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.end_at') </label>
                                            <input name="end_at" type="time" class="form-control"
                                                   value="{{$ads->end_at}}"
                                                   placeholder="@lang('messages.end_at')">
                                            @if ($errors->has('end_at'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_at') }}</strong>
                                        </span>
                                            @endif
                                        </div>


                                        <label class="control-label"> @lang('messages.days') </label>
                                        <br>
                                        <input id="select-all" type="checkbox"><label
                                            for='select-all'> {{app()->getLocale() == 'ar' ? 'اختيار الكل':'Choose All' }}</label>
                                        <br>

                                        <?php $days = \App\Models\Day::all(); $daysId = $ads->days->pluck('id')->toArray(); ?>
                                        @foreach($days as $day)
                                            <input type="checkbox" name="day_id[]" value="{{$day->id}}" {{in_array($day->id , $daysId) ? 'checked' : ''}}>
                                            {{app()->getLocale() == 'ar' ? $day->name_ar : $day->name_en}}
                                        @endforeach
                                        @if ($errors->has('day_id'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('day_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- image --}}
                                {{-- image editor --}}
                                {{-- <div class="form-group image-editor-preview">
                                    <label for="">{{ trans('messages.photo') }}</label>
                                    <label class="custom-label" data-toggle="tooltip" title="{{trans('dashboard.change_image')}}">
                                        <img class="rounded" id="avatar" src="{{asset(isset($ads->image_path) ? $ads->image_path : $restaurant->image_path)}}" alt="avatar" >
                                        <input type="file" class="sr-only" id="image-uploader" data-product_id="" name="image" accept="image/*">
                                    </label>
                                    
                                    @error('image_name')
                                        <p class="text-center text-danger">{{$message}}</p>
                                    @enderror
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <div class="alert text-center" role="alert"></div>
                                </div> --}}
                                <div class="form-group  image-editor-preview">
                                    <label class="control-label col-md-3"> @lang('messages.image') </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 200px; height: 150px; border: 1px solid black;">
                                                 @if($ads->content != null)
                                                    <img src="{{asset($ads->image_path)}}">
                                                @endif
                                            </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span
                                                        class="fileinput-new btn btn-info"> @lang('messages.choose_photo') </span>
                                                    <span
                                                        class="fileinput-exists btn btn-primary"> @lang('messages.change') </span>
                                                    <input type="file" name="image"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>
                                            </div>
                                        </div>
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                    </div>

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
     
    @php
        $itemId = $ads->id ;
        $editorRate = [3 , 4];
        $imageUploaderUrl = route('adminAds.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select[name=content_type]').on('change' , function(){
                var val = $(this).val();
                console.log(val);
                if(val == 'image'){
                    $('.form-group.image-editor-preview').fadeIn(200);
                    $('.form-group.content-link').fadeOut(200);
                    $('.form-group.content-link input').hide();
                }else if(val == 'youtube'){
                    $('.form-group.content-link').fadeIn(200);
                    $('.form-group.content-link input').show();
                    $('.form-group.image-editor-preview').fadeOut(200);
                }else{
                    $('.form-group.content-link').fadeOut(200);
                    $('.form-group.image-editor-preview').fadeOut(200);
                }
            });
            $('select[name=content_type]').trigger('change');
        });
    </script>

    <script type="text/javascript">

        function yesnoCheck() {
            if (document.getElementById('yesCheck').checked) {
                document.getElementById('ifYes').style.display = 'none';
            } else {
                document.getElementById('ifYes').style.display = 'block';
            }
        }
    </script>
    <script>
        $("#select-all").click(function () {
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
        });
    </script>
@endsection
