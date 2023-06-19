@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.copy') @lang('messages.menu_categories')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}">

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.copy') @lang('messages.menu_categories') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('menu_categories.index')}}">
                                @lang('messages.menu_categories')
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
                            <h3 class="card-title">@lang('messages.copy') @lang('messages.menu_categories') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('copyMenuCategoryPost')}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch') </label>
                                    <select name="branch_id[]" class="select2 form-control" required multiple>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">
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
                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.name_ar') </label>
                                        <input name="name_ar" type="text" class="form-control" value="{{$category->name_ar}}" placeholder="@lang('messages.name_ar')">
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
                                        <input name="name_en" type="text" class="form-control" value="{{$category->name_en}}" placeholder="@lang('messages.name_en')">
                                        @if ($errors->has('name_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                @if(Auth::guard('restaurant')->user()->ar == 'true')
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.description_ar') </label>
                                        <textarea class="textarea" name="description_ar" placeholder="@lang('messages.description_ar')"
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$category->description_ar}}</textarea>
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
                                        <textarea class="textarea" name="description_en" placeholder="@lang('messages.description_en')"
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$category->description_en}}</textarea>
                                        @if ($errors->has('description_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('description_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                {{--                                @if(\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->menu_arrange == 'true')--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label"> @lang('messages.arrange') </label>--}}
                                {{--                                        <input name="arrange" type="number" class="form-control" value="{{$category->arrange}}" placeholder="@lang('messages.arrange')">--}}
                                {{--                                        --}}{{--                                        <select name="arrange" class="form-control">--}}
                                {{--                                        --}}{{--                                            <option disabled selected> @lang('messages.choose_one') </option>--}}
                                {{--                                        --}}{{--                                            @for($i = 1 ; $i <= 100; $i++)--}}
                                {{--                                        --}}{{--                                                @if(\App\Models\MenuCategory::where('arrange' , $i)->whereRestaurantId(Auth::guard('restaurant')->user()->id)->first() == null)--}}
                                {{--                                        --}}{{--                                                    <option value="{{$i}}" {{$category->arrange == $i ? 'selected' : ''}}> {{$i}} </option>--}}
                                {{--                                        --}}{{--                                                @endif--}}
                                {{--                                        --}}{{--                                                @if($category->arrange == $i)--}}
                                {{--                                        --}}{{--                                                        <option value="{{$category->arrange}}" selected> {{$i}} </option>--}}
                                {{--                                        --}}{{--                                                @endif--}}
                                {{--                                        --}}{{--                                            @endfor--}}
                                {{--                                        --}}{{--                                        </select>--}}
                                {{--                                        @if ($errors->has('arrange'))--}}
                                {{--                                            <span class="help-block">--}}
                                {{--                                            <strong style="color: red;">{{ $errors->first('arrange') }}</strong>--}}
                                {{--                                        </span>--}}
                                {{--                                        @endif--}}
                                {{--                                    </div>--}}
                                {{--                                @endif--}}

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.time_activation') </label>
                                    <input name="time" type="radio" onclick="javascript:yesnoCheck();" id="noCheck" value="true" placeholder="@lang('messages.time')" {{$category->time == 'true' ? 'checked' : ''}}> @lang('messages.yes')
                                    <input name="time" type="radio" onclick="javascript:yesnoCheck();" id="yesCheck" value="false" placeholder="@lang('messages.time')" {{$category->time == 'false' ? 'checked' : ''}}> @lang('messages.no')

                                    @if ($errors->has('time'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('time') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div id="ifYes" style="display:{{$category->time == 'true' ? 'block' : 'none'}}">
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.start_at') </label>
                                            <input name="start_at" type="time" class="form-control" value="{{$category->start_at}}" placeholder="@lang('messages.start_at')">
                                            @if ($errors->has('start_at'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('start_at') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.end_at') </label>
                                            <input name="end_at" type="time" class="form-control" value="{{$category->end_at}}" placeholder="@lang('messages.end_at')">
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
                                                {{\App\Models\MenuCategoryDay::whereDayId($day->id)->where('menu_category_id' , $category->id)->first() != null ? 'checked' : ''}}
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

                                {{-- image copyor --}}
                                <div class="form-group image-copyor-preview">
                                    <label for="">{{ trans('messages.photo') }}</label>
                                    <label class="custom-label" data-toggle="tooltip" title="{{trans('dashboard.change_image')}}">
                                        <img class="rounded" id="avatar" src="{{asset(isset($category->image_path) ? $category->image_path : $restaurant->image_path)}}" alt="avatar" >
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
    @php
        $itemId = $category->id ;
        $imageUploaderUrl = route('restaurant.menu_category.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.js')}}"></script>
    <script>
        $(function(){
            $('.select2').select2({
                language: "{{app()->getLocale()}}" ,
                dir : "{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}"
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
        $("#select-all").click(function(){
            $("input[type=checkbox]").prop('checked',$(this).prop('checked'));
        });
    </script>
@endsection
