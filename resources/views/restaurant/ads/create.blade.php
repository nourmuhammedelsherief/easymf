@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('dashboard.ads')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link href="{{asset('admin')}}/bootstrap-fileinput/css/fileinput.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('admin/bootstrap-fileinput/css/fileinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/bootstrap-fileinput/css/fileinput-rtl.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('dashboard.ads') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.ads.index')}}">
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
                            <h3 class="card-title">@lang('messages.add') @lang('dashboard.ads') </h3>
                        </div>
                        <!-- /.card-header -->
                        @if($errors->any())
                            <p class="alert alert-danger text-center">{{$errors->first()}}</p>
                        @endif
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('restaurant.ads.store')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type="hidden" name="image_name" value="">
                            <input type="hidden" name="video_path" value="">
                            
                            <div class="card-body">
                                
                                     {{-- start_date --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.start_date') </label>
                                    <input type="date" name="start_date" class="form-control" value="{{old('start_date')}}">
                                    @if ($errors->has('start_date'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('start_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- end_date --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.end_date') </label>
                                    <input type="date" name="end_date" class="form-control" value="{{old('end_date')}}">
                                    @if ($errors->has('end_date'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.type') </label>
                                    <select name="type" id="" class="form-control " >
                                        <option value="">{{ trans('dashboard.choose') }}</option>
                                        <option value="main">{{ trans('dashboard.ads_main') }}</option>
                                        <option value="menu_category">{{ trans('dashboard.ads_category') }}</option>
                                        <option value="contact_us">{{ trans('dashboard.contact_us') }}</option>
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.menu_category') </label>
                                    <select name="category_id" id="" class="form-control " >
                                        <option value="">{{ trans('dashboard.choose') }}</option>
                                        @foreach ($menuCategories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- content_type --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.content_type') </label>
                                    <select name="content_type" id="" class="form-control " >
                                        <option value="">{{ trans('dashboard.choose') }}</option>
                                        <option value="image" {{old('content_type') == 'image' ? 'selected' : '' }}>{{ trans('dashboard.image') }}</option>
                                        <option value="youtube" {{old('content_type') == 'youtube' ? 'selected' : '' }}>{{ trans('dashboard.youtube') }}</option>
                                        <option value="local_video" {{old('content_type') == 'local_video' ? 'selected' : '' }}>{{ trans('dashboard.local_video') }}</option>
                                        <option value="gif" {{old('content_type') == 'gif' ? 'selected' : '' }}>{{ trans('dashboard.gif') }}</option>
                                    </select>
                                    @if ($errors->has('content_type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('content_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- url --}}
                                <div class="form-group content-link display-none">
                                    <label class="control-label"> @lang('dashboard.youtube') </label>
                                    <input type="text" name="link" class="form-control" value="{{old('content_type') == 'youtube' ? old('link') : ''}}" placeholder="مثال : xxxxxxx">
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
                                           placeholder="@lang('messages.time')" id="noCheck"> @lang('messages.yes')
                                    <input name="time" onclick="javascript:yesnoCheck();" type="radio" value="false"
                                           placeholder="@lang('messages.time')" id="yesCheck"> @lang('messages.no')
                                    @if ($errors->has('time'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('time') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div id="ifYes" style="display:none">
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.start_at') </label>
                                            <input name="start_at" type="time" class="form-control"
                                                   value="{{old('start_at')}}"
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
                                                   value="{{old('end_at')}}"
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
                                {{-- image editor --}}
                                <div class="form-group image-editor-preview display-none">
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
                                </div>
                                  {{-- video --}}
                                  <div class="form-group type_local display-none" style="margin-top: 2%;">
                                            <div class="col-md-12">
                                                <span class="fileinput-new"> {{ trans('dashboard.local_video') }}</span>
                                                <br>
                                                <div dir=rtl class="file-loading">
                                                    <input type="file" name="video" accept=".mp4" class="file" data-browse-on-zone-click="true" id="local-video" >
                                                </div>
                                            </div> 
                                    </div>
                                <div class="form-group image-gif display-none" style="margin-top: 2%;">
                                        <div class="col-md-12">
                                            <span class="fileinput-new"> {{ trans('messages.photo') }}</span>
                                            <br>
                                            <div dir=rtl class="file-loading">
                                                <input type="file" name="video" accept=".gif" class="file" data-browse-on-zone-click="true" id="gif_image" >
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
        // $itemId = $ads->id ;
        $editorRate = [3 , 4];
        $imageUploaderUrl = route('restaurant.ads.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')
@endsection
@section('scripts')
            
<script src="{{asset('admin')}}/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
<script src="{{ asset('admin/bootstrap-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('admin/bootstrap-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('admin/bootstrap-fileinput/js/plugins/purify.min.js') }}"></script>
<script src="{{ asset('admin/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('admin/bootstrap-fileinput/themes/fa/theme.js') }}"></script>

<script src="{{ asset('admin/bootstrap-fileinput/locales/ar.js') }}"></script>
<script src="https://use.fontawesome.com/52e183519a.js"></script>
<script>
        $("#local-video").fileinput({
            uploadUrl: "{{route('ads.uploadVideo')}}",
            // enableResumableUpload: true,
            resumableUploadOptions: {
            // uncomment below if you wish to test the file for previous partial uploaded chunks
            // to the server and resume uploads from that point afterwards
            // testUrl: "http://localhost/test-upload.php"
            },
            uploadExtraData: {
                '_token': '{{csrf_token()}}', // for access control / security 
                'type' : 'local_video' , 
                
            },
            rtl: true,
            language: 'ar' , 
            maxFileCount: 1,
            allowedFileTypes: ['video'],
            showCancel: true,
            showRemove: true,
            showUpload: true,
            showCancel: true,
            
            maxFilePreviewSize: 50240,
            initialPreviewAsData: true,
            overwriteInitial: true,            
       
            initialPreviewAsData: true,
            initialPreviewFileType: 'video',
       
       
            theme: 'fa',
            // deleteUrl: "http://localhost/file-delete.php"
        }).on('fileuploaded', function(event, previewId, index, fileId) {
            var response = previewId.response;
            if(response.status == 1){
                $('input[name=video_path]').val(response.video_path);
            }
        }).on('fileuploaderror', function(event, data, msg) {
            // console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
            console.log(msg);
            console.log(data);
        }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
            console.log('completed');
        });

        $("#gif_image").fileinput({
            uploadUrl: "{{route('ads.uploadVideo')}}",
            // enableResumableUpload: true,
            resumableUploadOptions: {
            // uncomment below if you wish to test the file for previous partial uploaded chunks
            // to the server and resume uploads from that point afterwards
            // testUrl: "http://localhost/test-upload.php"
            },
            uploadExtraData: {
                '_token': '{{csrf_token()}}', // for access control / security 
                'type' : 'gif'
                
            },
            rtl: true,
            language: 'ar' , 
            maxFileCount: 1,
            allowedFileTypes: ['image'],
            showCancel: true,
            showRemove: true,
            showUpload: true,
            showCancel: true,
            
            maxFilePreviewSize: 50240,
            initialPreviewAsData: true,
            overwriteInitial: true,            
       
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
       
       
            theme: 'fa',
            // deleteUrl: "http://localhost/file-delete.php"
        }).on('fileuploaded', function(event, previewId, index, fileId) {
            var response = previewId.response;
            if(response.status == 1){
                $('input[name=video_path]').val(response.video_path);
            }
        }).on('fileuploaderror', function(event, data, msg) {
            // console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
            console.log(msg);
            console.log(data);
        }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
            console.log('completed');
        });

   </script>
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select[name=type]').on('change' , function(){
                var thisTag = $(this);
                if(thisTag.val() == 'main'){
                    $('select[name=category_id]').parent().fadeOut(100);
                }else if(thisTag.val() == 'menu_category'){
                    $('select[name=category_id]').parent().fadeIn(100);
                }
            });
            
            $('select[name=content_type]').on('change' , function(){
                var val = $(this).val();
                if(val == 'image'){
                    $('.form-group.image-editor-preview').fadeIn(20);
                    $('.form-group.content-link').fadeOut(20);
                    $('.form-group.type_local').fadeOut(20);
                    $('.image-gif').fadeOut(20);
                    $('.form-group.content-link input').hide();
                }else if(val == 'youtube'){
                    $('.form-group.content-link').fadeIn(20);
                    $('.form-group.content-link input').show();
                    $('.form-group.type_local').fadeOut(20);
                    $('.image-gif').fadeOut(20);
                    $('.form-group.image-editor-preview').fadeOut(20);
                }else if(val == 'local_video'){
                    $('.form-group.content-link').fadeOut(20);
                    $('.form-group.content-link input').show();
                    $('.form-group.type_local').fadeIn(20);
                    $('.image-gif').fadeOut(20);
                    $('.form-group.image-editor-preview').fadeOut(20);
                }else if(val == 'gif'){
                    $('.form-group.content-link').fadeOut(20);
                    $('.form-group.content-link input').show();
                    $('.form-group.type_local').fadeOut(20);
                    $('.image-gif').fadeIn(20);
                    $('.form-group.image-editor-preview').fadeOut(20);
                }else{
                    $('.form-group.content-link').fadeOut(20);
                    $('.form-group.image-editor-preview').fadeOut(20);
                    $('.form-group.type_local').fadeOut(20);
                    $('.image-gif').fadeOut(20);
                }
            });
            $('select[name=content_type]').trigger('change')
            $('select[name=category_id]').parent().fadeOut(1);
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
