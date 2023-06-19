@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('dashboard.' . $type)
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}

    <style>
        #times{
            /* margin-top: 50px; */
            /* margin-left: 15px;
            margin-right: 15px; */
            padding: 10px;
        }
        #times > h2{
            margin-top: 50px;
        }
        #times .time{
            border: 1px solid #CCC;
            padding: 10px;
            border-radius: 10px;
            margin-top : 10px;
        }
        #times .time .delete-item{
            position: relative;
        }
        #times .time .delete-item > span{
            position: absolute;
            top: -80px;
            left: -12px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        #times .time .delete-item > span i{
            transition: 0.3s ease;   
        }
        #times .time .delete-item > span:hover i {
            color : red;
            box-shadow: 1px 1px 10px #CCC;
            transition: 0.3s ease;
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('dashboard.' . $type) </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.reservation.index')}}">
                                @lang('dashboard.reservations')
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.reservation.tables.index')}}">
                                @lang('dashboard.tables')
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
                <div class="col-md-12">
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.add') @lang('dashboard.' . $type) </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @if($errors->any())
                            <h4 class="alert alert-danger ">{{$errors->first()}}</h4>
                        @endif
                        <form role="form" id="post-form" action="{{route('restaurant.reservation.tables.store')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <div class="card-body">
                                @if($type == 'chair')
                                <input type="hidden" name="type" value="chair">
                                @elseif($type == 'package') 
                                <input type="hidden" name="type" value="package">
                                @else 
                                <input type="hidden" name="type" value="table">
                                @endif
                                <div class="row">
                                    {{-- branch_id --}}
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.branch') </label>
                                        <select name="branch_id" id="" class="select2 form-control ">
                                            @foreach ($branches as $item)
                                                <option value="{{$item->id}}" {{old('branch_id') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('branch_id'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    {{-- place_id --}}
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.place') </label>
                                        <select name="place_id" id="" class="select2 form-control ">
                                            @foreach ($places as $item)
                                                <option value="{{$item->id}}" {{old('place_id') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('place_id'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('place_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                   @if($type == 'chair' )
                                        {{-- min chair --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.chair_min') </label>
                                            <input type="number" name="chair_min" step="1" min="1" class="form-control" value="{{old('chair_min')}}" required>
                                            @if ($errors->has('chair_min'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('chair_min') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                         {{-- max chair --}}
                                         <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.chair_max') </label>
                                            <input type="number" name="chair_max" step="1" min="1" class="form-control" value="{{old('chair_max')}}" required>
                                            @if ($errors->has('chair_max'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('chair_max') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                   @elseif($type == 'table')
                                           {{-- people_count --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.people_count')</label>
                                            <input type="number" name="people_count" step="1" min="1" class="form-control" value="{{old('people_count')}}" required>
                                            @if ($errors->has('people_count'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('people_count') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- table_count --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.table_count') </label>
                                            <input type="number" name="table_count" step="1" min="1" class="form-control" value="{{old('table_count')}}" required>
                                            @if ($errors->has('table_count'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('table_count') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($type == 'package')
                                        {{-- people count --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.people_count') </label>
                                            <input type="number" name="people_count" step="1" min="1" class="form-control" value="{{old('people_count')}}" required>
                                            @if ($errors->has('people_count'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('people_count') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- chair min --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.reservation_min') </label>
                                            <input type="number" name="chair_min" step="1" min="1" class="form-control" value="{{old('chair_min')}}" required>
                                            @if ($errors->has('chair_min'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('chair_min') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- chair max --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.reservation_max') </label>
                                            <input type="number" name="chair_max" step="1" min="1" class="form-control" value="{{old('chair_max')}}" required>
                                            @if ($errors->has('chair_max'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('chair_max') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                   @endif
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.price_' . $type) </label>
                                        <input type="number" name="price" step="1" min="1" class="form-control" value="{{old('price')}}" required>
                                        @if ($errors->has('price'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @if ($type == 'package')
                                        
                                        {{-- title_en --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.name_ar') </label>
                                            <input type="text" name="title_ar"  class="form-control" value="{{old('title_ar')}}" required>
                                            @if ($errors->has('title_ar'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('title_ar') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- title_ar --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.name_en') </label>
                                            <input type="text" name="title_en"  class="form-control" value="{{old('title_en')}}" required>
                                            @if ($errors->has('title_en'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('title_en') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- description_ar --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.description_ar') </label>

                                            <textarea class="textarea" name="description_ar"
                                            placeholder="@lang('messages.description_ar')"
                                            style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                            @if ($errors->has('description_ar'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('description_ar') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- description_en --}}
                                        <div class="form-group col-md-6">
                                            <label class="control-label"> @lang('dashboard.entry.description_en') </label>

                                            <textarea class="textarea" name="description_en"
                                            placeholder="@lang('messages.description_en')"
                                            style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                            @if ($errors->has('description_en'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('description_en') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                      
                                        {{-- images --}}
                                        <div class="form-group col-12">
                                            <label class="control-label col-md-3"> @lang('messages.image') </label>
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

                                        <div class="form-group col-md-12">
                                            <label class="control-label"> @lang('dashboard.add_images') </label>

                                            <input type="file" name="images[]" class="form-control" multiple>
                                            @if ($errors->has('images'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('images') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    {{-- dates --}}
                                    <div class="form-group col-md-12">
                                        <label class="control-label"> @lang('dashboard.entry.dates') </label>
                                        <select name="dates[]" id="" class="select2 form-control " multiple>
                                            @foreach ($dates as $item)
                                                <option value="{{$item}}" {{old('d') == $item ? 'selected' : ''}}>{{$item}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('dates'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('dates') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    
                                </div>
                                <div class=" " id="times">
                                    <h2 class="text-center">{{ trans('dashboard.times') }}</h2>
                                    <button class="btn btn-success" type="button" data-count="0" id="add-time">{{ trans('dashboard.add_time') }}</button>

                                    <div class="times-list">
                                        <div class="row time " id="time-0" data-count="0">
                                            <div class="col-md-6">
                                                <label for="">{{ trans('dashboard.entry.from_time') }}</label>
                                                <input type="time" class="form-control" name="times[0][from]">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">{{ trans('dashboard.entry.to_time') }}</label>
                                                <input type="time" class="form-control" name="times[0][to]">
                                                <div class="delete-item">
                                                    <span><i class="fas fa-times"></i></span>
                                                </div>
                                            </div>
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
    
    @php
        // $itemId = $ads->id ;
        $editorRate = [3 , 4];
        $imageUploaderUrl = route('restaurant.ads.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(document).ready(function () {
           $('.select2').select2(); 
           $('#add-time').on('click' , function(){
                var tag = $(this);
                var count =  tag.data('count') + 1;
                var content = '<div class="row time " id="time-'+count+'" data-count="'+count+'">\
                                            <div class="col-md-6">\
                                                <label for="">{{ trans('dashboard.entry.from_time') }}</label>\
                                                <input type="time" class="form-control" name="times['+count+'][from]">\
                                            </div>\
                                            <div class="col-md-6">\
                                                <label for="">{{ trans('dashboard.entry.to_time') }}</label>\
                                                <input type="time" class="form-control" name="times['+count+'][to]">\
                                                <div class="delete-item">\
                                                    <span><i class="fas fa-times"></i></span>\
                                                </div>\
                                            </div>\
                                        </div>';

                $('#times .times-list').append(content);
                tag.data('count' , count);
           });

           $('#times').on('click' , '.delete-item span'  , function(){
                $(this).parent().parent().parent().remove();
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
