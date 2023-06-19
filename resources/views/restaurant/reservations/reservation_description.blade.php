@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.reservation_description')
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
                    <h1> @lang('messages.edit') @lang('dashboard.reservation_description') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.reservation.place.index')}}">
                                @lang('dashboard.reservation_description')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.reservation_description') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('restaurant.reservation.description.edit')}}"
                              method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <div class="card-body">

                                {{-- name_en --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.reservation_description_ar') </label>
                                    <textarea class="textarea"
                                        style="width: 100%; height: 400px; font-size: 14px;  border: 1px solid #dddddd; padding: 10px;"
                                        name="reservation_description_ar">{{$restaurant->reservation_description_ar}}</textarea>
                                    @if ($errors->has('reservation_description_ar'))
                                        <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('reservation_description_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.reservation_description_en') </label>
                                    <textarea class="textarea"
                                        style="width: 100%; height: 200px; font-size: 14px;  border: 1px solid #dddddd; padding: 10px;"
                                        name="reservation_description_en">{{$restaurant->reservation_description_en}}</textarea>

                                    @if ($errors->has('reservation_description_en'))
                                        <span class="help-block">
                                            <strong
                                                style="color: red;">{{ $errors->first('reservation_description_en') }}</strong>
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
