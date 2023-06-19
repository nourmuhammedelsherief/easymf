@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.products')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/cropper.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    
     --}}
    <link href="{{asset('admin')}}/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('admin/bootstrap-fileinput/css/fileinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/bootstrap-fileinput/css/fileinput-rtl.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}
    <script src="{{asset('admin/js/jquery.min.js')}}"></script>
    <script src="{{asset('admin/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('admin/js/additional-methods.min.js')}}"></script>


    <style>
        .error {
            color: red;
            display: block !important;
        }

        label {
            display: block;
            width: 100%;
            font-size: 15px;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.products') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('products.index')}}">
                                @lang('messages.products')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.products') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('products.update' , $product->id)}}"
                              method="post" enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch') </label>
                                    <select {{$product->branch->foodics_status == 'true' ? 'disabled': ''}} name="branch_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($branches as $branch)
                                            <option
                                                value="{{$branch->id}}" {{$product->branch_id == $branch->id ? 'selected' : ''}}>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$branch->name_ar == null ? $branch->name_en : $branch->name_ar}}
                                                @else
                                                    {{$branch->name_en == null ? $branch->name_ar : $branch->name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.menu_category') </label>
                                    <select id="menu_category_id" {{$product->branch->foodics_status == 'true' ? 'disabled': ''}} name="menu_category_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @if($product->menu_category_id != null)
                                            <?php $cats = \App\Models\MenuCategory::whereBranchId($product->branch_id)->get(); ?>
                                            @foreach($cats as $cat)
                                                <option
                                                    value="{{$cat->id}}" {{$product->menu_category_id == $cat->id ? 'selected' : ''}}>
                                                    {{app()->getLocale() == 'ar' ? $cat->name_ar : $cat->name_en}}
                                                </option>
                                            @endforeach
                                        @endif
                                        {{--                                        @foreach($categories as $category)--}}
                                        {{--                                            <option--}}
                                        {{--                                                value="{{$category->id}}" {{$product->menu_category_id == $category->id ? 'selected' : ''}}>--}}
                                        {{--                                                @if(app()->getLocale() == 'ar')--}}
                                        {{--                                                    {{$category->name_ar == null ? $category->name_en : $category->name_ar}}--}}
                                        {{--                                                @else--}}
                                        {{--                                                    {{$category->name_en == null ? $category->name_ar : $category->name_en}}--}}
                                        {{--                                                @endif--}}
                                        {{--                                            </option>--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                    @if ($errors->has('menu_category_id'))
                                        <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('menu_category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if($product->sub_category_id != null)
                                    @php
                                        $sub_cats = \App\Models\RestaurantSubCategory::where('menu_category_id', $product->menu_category_id)->where('id' , '!=' , $product->sub_category_id)->get();
                                    @endphp
                                @else
                                    @php
                                        $sub_cats = \App\Models\RestaurantSubCategory::where('menu_category_id', $product->menu_category_id)->get();
                                    @endphp
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.sub_categories') </label>
                                    <select id="sub_category" name="sub_category_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @if($sub_cats->count() > 0)
                                            @foreach($sub_cats as $sub_cat)
                                                <option value="{{$sub_cat->id}}">
                                                    {{app()->getLocale() == 'ar' ? $sub_cat->name_ar : $sub_cat->name_en}}
                                                </option>
                                            @endforeach
                                        @endif
                                        @if($product->sub_category_id != null)
                                            <option value="{{$product->sub_category_id}}"
                                                    selected> {{app()->getLocale() == 'ar' ? $product->sub_category->name_ar : $product->sub_category->name_en}} </option>
                                        @endif
                                    </select>
                                    @if ($errors->has('sub_category_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('sub_category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_ar') </label>
                                        <input  name="name_ar" type="text" class="form-control"
                                               value="{{$product->name_ar}}" placeholder="@lang('messages.name_ar')">
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
                                        <input  name="name_en" type="text" class="form-control"
                                               value="{{$product->name_en}}" placeholder="@lang('messages.name_en')">
                                        @if ($errors->has('name_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                @php
                                    $loyaltySubscription =   \App\Models\ServiceSubscription::whereRestaurantId(auth('restaurant')->id())->whereHas('service' , function($query){
                                            $query->where('id' , 11);
                                        })
                                            ->whereIn('status' , ['active' , 'tentative'])
                                            ->first()
                                @endphp
                                @if(isset($loyaltySubscription->id))
                                <div class="form-group loyalty-points">
                                    <label class="control-label"> @lang('dashboard.entry.loyalty_points') </label>
                                    <input name="loyalty_points"  type="number" class="form-control" value="{{$product->loyalty_points}}"
                                        placeholder="@lang('dashboard.entry.loyalty_points')">
                                    @if ($errors->has('loyalty_points'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('loyalty_points') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.price') </label>
                                    <input {{$product->branch->foodics_status == 'true' ? 'disabled': ''}}  step="0.1" name="price" type="number" class="form-control" value="{{$product->price}}"
                                           placeholder="@lang('messages.price')">
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.price_before_discount') </label>
                                    <input name="price_before_discount" type="number" class="form-control"
                                           value="{{$product->price_before_discount}}"
                                           placeholder="@lang('messages.price_before_discount')">
                                    @if ($errors->has('price_before_discount'))
                                        <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('price_before_discount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.calories') </label>
                                    <input name="calories" type="number" class="form-control"
                                           value="{{$product->calories}}" placeholder="@lang('messages.calories')">
                                    @if ($errors->has('calories'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('calories') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.sensitivities') </label>
                                    <select name="sensitivity_id[]" id="" class="select2 form-control " multiple>
                                        @foreach ($sensitivities as $sensitivity)
                                            <option {{\App\Models\ProductSensitivity::whereProductId($product->id)->where('sensitivity_id' , $sensitivity->id)->first() != null ? 'selected' : ''}}
                                                    value="{{$sensitivity->id}}" {{old('d') == $sensitivity->id ? 'selected' : ''}}>
                                                {{app()->getLocale() == 'ar' ? $sensitivity->name_ar : $sensitivity->name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('sensitivity_id'))
                                        <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('sensitivity_id') }}</strong>
                                            </span>
                                    @endif
                                </div>

                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.description_ar') </label>
                                        <textarea class="textarea" name="description_ar"
                                                  placeholder="@lang('messages.description_ar')"
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$product->description_ar}}</textarea>
                                        @if ($errors->has('description_ar'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('description_ar') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                @if(Auth::guard('restaurant')->user()->en == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.description_en') </label>
                                        <textarea class="textarea" name="description_en"
                                                  placeholder="@lang('messages.description_en')"
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$product->description_en}}</textarea>
                                        @if ($errors->has('description_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('description_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.posters') </label>
                                    <select name="poster_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="" {{$product->poster_id == null ? 'selected' : ''}}> @lang('messages.no_poster') </option>
                                        @foreach($posters as $poster)
                                            <option
                                                value="{{$poster->id}}" {{$product->poster_id == $poster->id ? 'selected' : ''}}>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$poster->name_ar == null ? $poster->name_en : $poster->name_ar}}
                                                @else
                                                    {{$poster->name_en == null ? $poster->name_ar : $poster->name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('poster_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('poster_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- video_type --}}
                                <div class="form-group ">
                                    <label class="control-label"> @lang('dashboard.entry.video_type') </label>
                                    <select name="video_type" id="video_type" class="form-control select2">
                                        <option value="" selected>{{ trans('dashboard.image') }}</option>
                                        <option value="local_video" {{$product->video_type == 'local_video' ? 'selected' : ''}}>{{ trans('dashboard.local_video') }}</option>
                                        <option value="youtube" {{$product->video_type == 'youtube' ? 'selected' : ''}}>{{ trans('dashboard.youtube') }}</option>
                                        <option value="gif" {{$product->video_type == 'gif' ? 'selected' : ''}}>{{ trans('dashboard.image_gif') }}</option>
                                    </select>
                                    @if ($errors->has('video_type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('video_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- video_link --}}
                                <div class="form-group content-link video_type_youtube display-none">
                                    <label class="control-label"> @lang('dashboard.youtube') </label>
                                    <input type="text" name="video_id" class="form-control" value="{{$product->video_type== 'youtube' ? $product->video_id : ''}}" placeholder="مثال : xxxxxxx">
                                    <p class="text-mute">{{ trans('dashboard.youtube_link_code') }}</p>
                                    @if ($errors->has('video_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('video_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- video --}}
                                <div class="form-group video_type_local display-none" style="margin-top: 2%;">
                                    <div class="col-md-12">
                                        <span class="fileinput-new"> {{ trans('dashboard.local_video') }}</span>
                                        <br>
                                        <div dir=rtl class="file-loading">
                                            <input type="file" name="video" accept=".mp4" class="file" data-browse-on-zone-click="true" id="local-video" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.time_activation') </label>
                                    <input name="time" type="radio" onclick="javascript:yesnoCheck();" id="noCheck"
                                           value="true"
                                           placeholder="@lang('messages.time')" {{$product->time == 'true' ? 'checked' : ''}}> @lang('messages.yes')
                                    <input name="time" type="radio" onclick="javascript:yesnoCheck();" id="yesCheck"
                                           value="false"
                                           placeholder="@lang('messages.time')" {{$product->time == 'false' ? 'checked' : ''}}> @lang('messages.no')

                                    @if ($errors->has('time'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('time') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- date --}}
                                <div class="form-group">
                                    <div id="ifYes" style="display:{{$product->time == 'true' ? 'block' : 'none'}}">
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.start_at') </label>
                                            <input name="start_at" type="time" class="form-control"
                                                   value="{{$product->start_at}}"
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
                                                   value="{{$product->end_at}}"
                                                   placeholder="@lang('messages.end_at')">
                                            @if ($errors->has('end_at'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_at') }}</strong>
                                        </span>
                                            @endif
                                        </div>

                                        <label class="control-label"> @lang('messages.days') </label>
                                        <br>
                                        <input id="select-all" type="checkbox"><label for='select-all'>
                                            {{app()->getLocale() == 'ar' ? 'اختيار الكل':'Choose All' }}
                                        </label>
                                        <br>

                                        <?php $days = \App\Models\Day::all(); ?>
                                        @foreach($days as $day)
                                            <input type="checkbox" name="day_id[]" value="{{$day->id}}"
                                                {{\App\Models\ProductDay::whereDayId($day->id)->where('product_id' , $product->id)->first() != null ? 'checked' : ''}}
                                            >
                                            {{app()->getLocale() == 'ar' ? $day->name_ar : $day->name_en}}
                                        @endforeach
                                        @if ($errors->has('day_id'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('day_id') }}</strong>
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

                                <div class="form-group image-editor-preview display-none">
                                    <label for="">{{ trans('messages.photo') }}</label>
                                    <label class="custom-label" data-toggle="tooltip"
                                           title="{{trans('dashboard.change_image')}}">
                                        <img class="rounded" id="avatar"
                                             src="{{asset($product->image_path)}}" alt="avatar">
                                        <input type="file" class="sr-only" id="image-uploader"
                                               data-product_id="{{$product->id}}" name="image" accept="image/*">
                                    </label>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                             role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            0%
                                        </div>
                                    </div>
                                    <div class="alert text-center" role="alert"></div>
                                    @if($product->photo != null)
                                        <a href="{{route('deleteProductPhoto' , $product->id)}}" class="btn btn-danger"> {{ trans('dashboard.delete_image') }}</a>
                                    @endif
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

            <script src="{{asset('admin')}}/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
            <script src="{{ asset('admin/bootstrap-fileinput/js/plugins/piexif.min.js') }}"></script>
            <script src="{{ asset('admin/bootstrap-fileinput/js/plugins/sortable.min.js') }}"></script>
            <script src="{{ asset('admin/bootstrap-fileinput/js/plugins/purify.min.js') }}"></script>
            <script src="{{ asset('admin/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
            <script src="{{ asset('admin/bootstrap-fileinput/themes/fa/theme.js') }}"></script>

            <script src="{{ asset('admin/bootstrap-fileinput/locales/ar.js') }}"></script>
            <script src="https://use.fontawesome.com/52e183519a.js"></script>
            <script>
                   var xbranches = {!! json_encode($branchesSubscription) !!};
                    $(function(){
                        $('select[name=branch_id]').on('change' , function(){
                            var tag = $(this); 
                            $('.loyalty-points').hide(100);
                            $.each(xbranches , function(k , v){
                                if(v == tag.val()){
                                    $('.loyalty-points').fadeIn(300);
                                    console.log('done');
                                }
                            });
                        });
                        $('select[name=branch_id]').trigger('change');
                    });
                $("#local-video").fileinput({
                    uploadUrl: "{{route('products.uploadVideo')}}",
                    // enableResumableUpload: true,
                    resumableUploadOptions: {
                        // uncomment below if you wish to test the file for previous partial uploaded chunks
                        // to the server and resume uploads from that point afterwards
                        // testUrl: "http://localhost/test-upload.php"
                    },
                    uploadExtraData: {
                        '_token': '{{csrf_token()}}', // for access control / security 

                        'product_id' : {{$product->id}}
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
                    initialPreview: [
                        @if(!empty($product->video_id))
                            '<video  controls class="kv-preview-data file-preview-video file-zoom-detail"><source src="{{asset($product->video_id)}}" type="video/mp4"></video>'
                        @endif
                    ],
                    initialPreviewAsData: true,
                    initialPreviewFileType: 'video',
                    initialPreviewConfig: [
                            @if(!empty($product->video_id))
                        {caption: "Video",
                            previewAsData : false,
                            key: "1"}
                        @endif
                    ],

                    theme: 'fa',
                    // deleteUrl: "http://localhost/file-delete.php"
                }).on('fileuploaded', function(event, previewId, index, fileId) {
                    console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
                }).on('fileuploaderror', function(event, data, msg) {
                    // console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
                    console.log(msg);
                    console.log(data);
                }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                    console.log('File Batch Uploaded', preview, config, tags, extraData);
                });

                $("#gif_image").fileinput({
                    uploadUrl: "{{route('products.uploadVideo')}}",
                    // enableResumableUpload: true,
                    resumableUploadOptions: {
                    },
                    uploadExtraData: {
                        '_token': '{{csrf_token()}}', // for access control / security 
                        'type' : 'gif' , 
                        'product_id' : {{$product->id}}
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
                    initialPreview: [
                        @if(!empty($product->photo))
                            '{{asset("uploads/products/" . $product->photo)}}' 
                        @endif
                    ],
                    initialPreviewFileType: 'image',
                    initialPreviewConfig: [
                            @if(!empty($product->photo))
                        {caption: "Image",
                            previewAsData : true,
                            key: "1"}
                        @endif
                    ],

                    theme: 'fa',
                    // deleteUrl: "http://localhost/file-delete.php"
                }).on('fileuploaded', function(event, previewId, index, fileId) {
                    console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
                }).on('fileuploaderror', function(event, data, msg) {
                    // console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
                    console.log(msg);
                    console.log(data);
                }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                    console.log('File Batch Uploaded', preview, config, tags, extraData);
                });

                if ($("#post-form").length > 0) {
                    $("#post-form1").validate({

                        rules: {
                            name_ar: {
                                required: {{\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->ar == 'true' ? true : false}},
                                maxlength: 191,
                                // unique: true,
                            },
                            name_en: {
                                required: {{\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->ar == 'true' ? true : false}},
                                maxlength: 191
                            },
                            {{--description_ar: {--}}
                                {{--    required: {{\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->ar == 'true' ? true : false}},--}}
                                {{--    // unique: true,--}}
                                {{--},--}}
                                {{--description_en: {--}}
                                {{--    required: {{\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->ar == 'true' ? true : false}},--}}
                                {{--},--}}
                            branch_id: {
                                required: true,
                            },
                            menu_category_id: {
                                required: true,
                            },
                            country_id: {
                                required: true,
                            },
                            poster_id: {
                                required: false,
                            },
                            sub_category_id: {
                                required: false,
                            },
                            price: {
                                required: true,
                                maxlength: 11
                            },

                            active: {
                                required: true,
                            },

                        },
                        messages: {
                            name_ar: {
                                required: "{{trans('messages.name_ar')}}" + " " + "{{trans('messages.required')}}",
                                maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.name_ar')}}" + "191",
                            },
                            name_en: {
                                required: "{{trans('messages.name_en')}}" + " " + "{{trans('messages.required')}}",
                                maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.name_en')}}" + "191",
                            },
                            branch_id: {
                                required: "{{trans('messages.branch')}}" + " " + "{{trans('messages.required')}}",
                            },
                            menu_category_id: {
                                required: "{{trans('messages.menu_category')}}" + " " + "{{trans('messages.required')}}",
                            },

                            price: {
                                required: "{{trans('messages.price')}}" + " " + "{{trans('messages.required')}}",
                                maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.price')}}" + "8",
                            },

                            {{--description_ar: {--}}
                                {{--    required: "{{trans('messages.description_ar')}}" +" "+ "{{trans('messages.required')}}",--}}
                                {{--},--}}
                                {{--description_en: {--}}
                                {{--    required: "{{trans('messages.description_en')}}" +" "+ "{{trans('messages.required')}}",--}}
                                {{--},--}}
                            active: {
                                required: "{{trans('messages.active')}}" + " " + "{{trans('messages.required')}}",
                            },
                            {{--photo: {--}}
                            {{--    required: "{{trans('messages.photo')}}" +" "+ "{{trans('messages.required')}}",--}}
                            {{--},--}}



                        },
                        submitHandler: function (form) {
                            // event.preventDefault();
                            var formData = new FormData($(this)[0]);
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $('#send_form').html('أرسال..');
                            $.ajax({

                                url: "{{ route('products.update' , $product->id) }}",
                                type: "POST",
                                data: formData,
                                success: function (response) {
                                    if (response.errors && response.errors.length > 0) {
                                        jQuery.each(response.errors, function (key, value) {
                                            jQuery('.alert-danger').show();
                                            jQuery('.alert-danger').append('<p>' + value + '</p>');
                                        });
                                    } else {
                                        $('#send_form').html('Submit');
                                        $('#res_message').show();
                                        $('#res_message').html(response.msg);
                                        $('#msg_div').removeClass('d-none');

                                        document.getElementById("post-form").reset();
                                        setTimeout(function () {
                                            $('#res_message').hide();
                                            $('#msg_div').hide();
                                        }, 10000);
                                        window.location = response.url;
                                    }
                                }
                            });
                        }
                    });
                }

            </script>

        </div><!-- /.container-fluid -->
    </section>
    @php
        $itemId = $product->id ;
        $imageUploaderUrl = route('restaurant.product.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script src="{{ URL::asset('admin/js/cropper.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            $('#productUploadImage').modal('show');
            $('select[name="menu_category_id"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/get/sub_categories/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $('#sub_category').empty();
                        // $('select[name="city_id"]').append("<option disabled selected> choose </option>");
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $('select[name="sub_category_id"]').append("<option disabled selected> @lang('messages.choose_one') </option>");
                        $.each(data, function (index, sub_categories) {
                            @if(app()->getLocale() == 'ar')
                            $('select[name="sub_category_id"]').append('<option value="' + sub_categories.id + '">' + sub_categories.name_ar + '</option>');
                            @else
                            $('select[name="sub_category_id"]').append('<option value="' + sub_categories.id + '">' + sub_categories.name_en + '</option>');
                            @endif
                        });
                    }
                });
            });

            $('select[name=video_type]').on('change' , function(){
                var tag= $(this);
                console.log('video_type');
                if(tag.val() == 'local_video'){
                    $('.video_type_local').fadeIn(200);
                    $('.video_type_youtube').fadeOut(200);
                    $('.image-editor-preview').fadeIn(200);
                    $('.image-gif').fadeOut(200);
                }
                else if(tag.val() == 'youtube'){
                    $('.video_type_local').fadeOut(200);
                    $('.video_type_youtube').fadeIn(200);
                    $('.image-editor-preview').fadeIn(200);
                    $('.image-gif').fadeOut(200);
                }
                else if(tag.val() == 'gif'){
                    $('.video_type_local').fadeOut(200);
                    $('.video_type_youtube').fadeOut(200);
                    $('.image-editor-preview').fadeOut(200);
                    $('.image-gif').fadeIn(200);
                }
                else{

                    $('.video_type_local').fadeOut(300);
                    $('.video_type_youtube').fadeOut(300);
                    $('.image-editor-preview').fadeIn(200);
                    $('.image-gif').fadeOut(200);
                }
            });
            $('select[name=video_type]').trigger('change');
            $('select[name="branch_id"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/get/categories/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $('#menu_category_id').empty();
                        // $('select[name="city_id"]').append("<option disabled selected> choose </option>");
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $('select[name="menu_category_id"]').append("<option disabled selected> @lang('messages.choose_one') </option>");
                        $.each(data, function (index, categories) {
                            @if(app()->getLocale() == 'ar')
                            $('select[name="menu_category_id"]').append('<option value="' + categories.id + '">' + categories.name_ar + '</option>');
                            @else
                            $('select[name="menu_category_id"]').append('<option value="' + categories.id + '">' + categories.name_en + '</option>');
                            @endif
                        });
                    }
                });
            });

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
