@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.link_contact_us')
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
                    <h1> @lang('messages.edit') @lang('dashboard.link_contact_us') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.link_contact_us.index')}}">
                                @lang('dashboard.link_contact_us')
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
                            <h3 class="card-title">@lang('messages.add') @lang('dashboard.link_contact_us') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('restaurant.link_contact_us.update' , $contact->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type="hidden" name="_method" value="PUT">
                            <div class="card-body">
                               
                                {{-- name ar --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.name_ar') </label>
                                    <input name="name_ar" type="text" class="form-control"
                                           value="{{$contact->name_ar}}" placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('name_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                        </span>
                                    @endif
                                

                                </div>
                                {{-- name_en --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.name_en') </label>
                                    <input name="name_en" type="text" class="form-control"
                                           value="{{$contact->name_en}}" placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- barcode --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.barcode') </label>
                                    <input name="barcode" type="text" class="form-control"
                                           value="{{$contact->barcode}}" placeholder="@lang('messages.barcode')">
                                    @if ($errors->has('barcode'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('barcode') }}</strong>
                                        </span>
                                    @endif
                                </div>

                       
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
            <script>
                if ($("#post-form").length > 0) {
                    $("#post-form").validate({

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
                        },
                        submitHandler: function (form) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            var formData = new FormData($(this)[0]);

                            $('#send_form').html('Sending..');
                            $.ajax({
                                url: "{{ route('employees.store') }}",
                                type: "POST",
                                data: $('#post-form').serialize(),
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
                    })
                }
            </script>

        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
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
