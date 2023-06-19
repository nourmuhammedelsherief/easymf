@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('dashboard.restaurant_contact_us')
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
                    <h1> @lang('messages.add') @lang('dashboard.restaurant_contact_us') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant.contact_us.index')}}">
                                @lang('dashboard.restaurant_contact_us')
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
                            <h3 class="card-title">@lang('messages.add') @lang('dashboard.restaurant_contact_us') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('restaurant.contact_us.store')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                               {{-- link_id --}}
                               <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.link_id') <span style="color:red">*</span></label>
                                    <select name="link_id" id="link_id" class="form-control select2" required>
                                        <option value="" disabled selected>{{ trans('dashboard.choose') }}</option>
                                        <option value="" selected>{{ trans('dashboard.default_link_') }}</option>
                                        @foreach ($links as $item)
                                            <option value="{{$item->id}}" {{old('link_id') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('link_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('link_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- name ar --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.name_ar') </label>
                                    <input name="title_ar" type="text" class="form-control"
                                           value="{{old('title_ar')}}" placeholder="@lang('messages.name_ar')">
                                    @if ($errors->has('title_ar'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('title_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- title_en --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.name_en') </label>
                                    <input name="title_en" type="text" class="form-control"
                                           value="{{old('title_en')}}" placeholder="@lang('messages.name_en')">
                                    @if ($errors->has('title_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('title_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- link --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.link') </label>
                                    <input name="url" type="text" class="form-control"
                                           value="{{old('url')}}" placeholder="@lang('messages.url')">
                                    @if ($errors->has('url'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('url') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                 {{-- sort --}}
                                 <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.entry.sort') </label>
                                    <input name="sort" type="text" class="form-control" required
                                           value="{{empty(old('sort')) ? $maxSort : old('sort')}}" placeholder="@lang('messages.sort')">
                                    @if ($errors->has('sort'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('sort') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- image --}}
                                <div class="form-group ">
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
                            title_ar: {
                                required: {{\Illuminate\Support\Facades\Auth::guard('restaurant')->user()->ar == 'true' ? true : false}},
                                maxlength: 191,
                                // unique: true,
                            },
                            title_en: {
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
                            title_ar: {
                                required: "{{trans('messages.name_ar')}}" + " " + "{{trans('messages.required')}}",
                                maxlength: "{{trans('messages.max_length')}}" + " " + "{{trans('messages.name_ar')}}" + "191",
                            },
                            title_en: {
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
                                url: "{{ route('restaurant.feedback.branch.store') }}",
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
