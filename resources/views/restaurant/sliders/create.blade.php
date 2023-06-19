@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.sliders')
@endsection

@section('style')
<link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}">
    <link href="{{asset('admin')}}/bootstrap-fileinput/css/fileinput.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('admin/bootstrap-fileinput/css/fileinput.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/bootstrap-fileinput/css/fileinput-rtl.min.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.sliders') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('sliders.index')}}">
                                @lang('messages.sliders')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.sliders') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('sliders.store')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type="hidden" name="video_path" value="">
                            <div class="card-body">
                            {{-- type --}}
                            <div class="form-group ">
                                <label class="control-label"> @lang('dashboard.entry.type') </label>
                                <select name="type" id="type" class="form-control select2">
                                    <option value="image" selected>{{ trans('dashboard.image') }}</option>
                                    <option value="local_video" {{old('type') == 'local_video' ? 'selected' : ''}}>{{ trans('dashboard.local_video') }}</option>
                                    <option value="youtube" {{old('type') == 'youtube' ? 'selected' : ''}}>{{ trans('dashboard.youtube') }}</option>
                                    <option value="gif" {{old('type') == 'gif' ? 'selected' : ''}}>{{ trans('dashboard.image_gif') }}</option>
                                </select>
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                                {{-- url --}}
                                <div class="form-group type_youtube display-none">
                                    <label class="control-label"> @lang('dashboard.youtube') </label>
                                    <input type="text" name="youtube" class="form-control" value="{{old('youtube') == 'youtube' ? old('youtube') : ''}}" placeholder="مثال : xxxxxxx">
                                    <p class="text-mute">{{ trans('dashboard.youtube_link_code') }}</p>
                                    @if ($errors->has('youtube'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('youtube') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- image --}}
                                <div class="form-group type_image display-none">
                                    <label class="control-label col-md-3"> @lang('messages.photo') </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 200px; height: 150px; border: 1px solid black;">
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

                                    {{-- image gif --}}
                                    <div class="form-group image-gif display-none" style="margin-top: 2%;">
                                        <div class="col-md-12">
                                            <span class="fileinput-new"> {{ trans('messages.photo') }}</span>
                                            <br>
                                            <div dir=rtl class="file-loading">
                                                <input type="file" name="video" accept=".gif" class="file" data-browse-on-zone-click="true" id="gif_image" >
                                            </div>
                                        </div> 
                                    </div>
                                {{-- video --}}
                                <div class="form-group type_local  display-none" style="margin-top: 2%;">
                                    <div class="col-md-12">
                                        <span class="fileinput-new"> {{ trans('dashboard.local_video') }}</span>
                                        <br>
                                        <div dir=rtl class="file-loading">
                                            <input type="file" name="video" accept=".mp4" class="file" data-browse-on-zone-click="true" id="local-video" >
                                        </div>
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
                uploadUrl: "{{route('sliders.uploadVideo')}}",
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
                uploadUrl: "{{route('sliders.uploadVideo')}}",
                // enableResumableUpload: true,
                resumableUploadOptions: {
                // uncomment below if you wish to test the file for previous partial uploaded chunks
                // to the server and resume uploads from that point afterwards
                // testUrl: "http://localhost/test-upload.php"
                },
                uploadExtraData: {
                    '_token': '{{csrf_token()}}', // for access control / security 
                    'type' : 'gif' , 
                    
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
    <script src="{{asset('plugins/select2/js/select2.js')}}"></script>
    <script>
        $(function(){
            $('.select2').select2();
            $('select[name=type]').on('change' , function(){
                var tag= $(this);
                console.log('type');
                if(tag.val() == 'local_video'){
                    $('.type_local').fadeIn(20);
                    $('.type_youtube').fadeOut(20);
                    $('.type_image').fadeOut(20);
                    $('.image-gif').fadeOut(20);
                }
                else if(tag.val() == 'youtube'){
                    
                    $('.type_local').fadeOut(20);
                    $('.type_youtube').fadeIn(20);
                    $('.type_image').fadeOut(20);
                    $('.image-gif').fadeOut(20);
                }
                else{
                    $('.type_local').fadeOut(20);
                    $('.type_youtube').fadeOut(20);
                    $('.type_image').fadeOut(20);
                    if(tag.val() == 'gif'){
                        $('.image-gif').fadeIn(20);
                        
                    }else{
                        $('.type_image').fadeIn(20);
                    }
                }
            });
            $('select[name=type]').trigger('change');
            // $('#type').on('change' , function(){
            //     var tag = $(this);
            //     if(tag.val() == 'youtube'){
            //         $('.form-group.youtube').fadeIn(200);
            //         $('.form-group.image').fadeOut(200);
            //     }else{
            //         $('.form-group.image').fadeIn(200);
            //         $('.form-group.youtube').fadeOut(200);
            //     }
            // });
            // $('#type').trigger('change');
        });
    </script>
@endsection
