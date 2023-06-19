@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.offers')
@endsection

@section('styles')
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.offers') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('offers.index')}}">
                                @lang('messages.offers')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.offers') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('offers.update' , $offer->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name') </label>
                                    <input name="name" type="text" class="form-control"
                                           value="{{$offer->name}}"
                                           placeholder="@lang('messages.name')">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- image editor --}}
                                <div class="form-group image-editor-preview">
                                    <label for="">{{ trans('messages.photo') }}</label>
                                    <label class="custom-label" data-toggle="tooltip" title="{{trans('dashboard.change_image')}}">
                                        <img class="rounded" id="avatar" src="{{asset(isset($offer->image_path) ? $offer->image_path : $restaurant->image_path)}}" alt="avatar" >
                                        <input type="file" class="sr-only" id="image-uploader" data-product_id="" name="image" accept="image/*">
                                    </label>
                                    {{-- time --}}
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.time_activation') </label>
                                        <input name="time" type="radio" onclick="javascript:yesnoCheck();" value="true"
                                               {{$offer->time == 'true' ? 'checked' : ''}} placeholder="@lang('messages.time')" id="noCheck"> @lang('messages.yes')
                                        <input name="time" onclick="javascript:yesnoCheck();" type="radio" value="false" {{$offer->time == 'false' ? 'checked' : ''}}
                                        placeholder="@lang('messages.time')" id="yesCheck"> @lang('messages.no')
                                        @if ($errors->has('time'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('time') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div id="ifYes" style="display:{{$offer->time == "true" ? 'block' : 'none'}}">
                                            <div class="form-group">
                                                <label class="control-label"> @lang('messages.start_at') </label>
                                                <input name="start_at" type="time" class="form-control"
                                                       value="{{$offer->start_at}}"
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
                                                       value="{{$offer->end_at}}"
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

                                            <?php $days = \App\Models\Day::all(); $daysId = $offer->days->pluck('id')->toArray(); ?>
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
                            @method('PUT')
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
        $itemId = $offer->id ;
        $editorRate = [3 , 4];
        $imageUploaderUrl = route('restaurant.offer.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')

@endsection
@section('scripts')
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
        $(".delete_image").click(function(){
            var id = $(this).attr('id');
            var url = '{{ route("imageOfferRemove", ":id") }}';

            url = url.replace(':id', id);

            //alert(image_id );
            $.ajax({
                url: url,
                type: 'GET',
                success: function(result) {
                    if (!result.message)
                    {
                        $(".img_"+id).fadeOut('1000');
                    }

                }
            });
        });
    </script>
@endsection
