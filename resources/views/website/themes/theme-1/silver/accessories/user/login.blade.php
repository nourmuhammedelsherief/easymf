<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<style>
    .error{
        color:red;
        display: block !important;
    }

</style>


@include('website.'.session('theme_path').'silver.layout.header')

<!-- header and footer bar go here-->
<div class="header header-fixed header-auto-show header-logo-app">


    <a class="header-title header-subtitle" href="{{route('sliverHome' , $restaurant->name_barcode)}}">
        <p>  <i class="fa fa-home"></i>
            @lang('messages.menu_back')

        </p>
    </a>

    {{--        <a href="#" data-menu="menu-map" class="header-title header-subtitle"><i class="fas fa-map-marker-alt"></i> فرع الدمام </a>--}}

    @if($restaurant->ar == 'true' && $restaurant->en == 'true')
        @if(app()->getLocale() == 'en')
            <a href="#" class="header-title header-subtitle" onclick="window.location='{{ route('language' , 'ar') }}'">
                ع
            </a>
            {{--            <a type="button" onclick="window.location='{{ route('language' , 'ar') }}'">ع</a>--}}
        @else
            <a href="#" class="header-title header-subtitle" onclick="window.location='{{ route('language' , 'en') }}'">
                En
            </a>
            {{--            <button type="button" onclick="window.location='{{ route('language' , 'en') }}'">En</button>--}}

        @endif
    @endif


</div>


<div class="page-content pb-0">


    <style>
        .pinBox {
            --width: 296px;
            --height: 74px;
            --spacing: 47px;
            direction: ltr;
            display: inline-block;
            position: relative;
            width: var(--width);
            height: var(--height);
            background-image: url(https://i.stack.imgur.com/JbkZl.png);
        }

        .pinEntry {

            padding-left: 21px;
            font-family: courier, monospaced;
            font-size: 47px !important;
            height: var(--height);
            letter-spacing: var(--spacing);
            background-color: transparent;
            border: 0;
            outline: none;
            clip: rect(0px, calc(var(--width) - 21px), var(--height), 0px);
        }
    </style>



    <div class="card   mb-0 pb-0">
        <div class="card-body p-1">

            <div class="card  mb-0">
                @include('flash::message')
                <div class="row pt-2 mb-0">
                    <div class="col-12">
                        <div class="card mx-4">
                            <div class="content mb-0">
                                <div class="text-center">
                                    <h3 class=" mb-3"> @lang('messages.UserLogin')</h3>
                                    <img src="{{asset('images/login.gif')}}" style="width:90px;" class="mb-3" />

                                </div>
                                <div class="text-center">
                                    <img src="{{asset('images/line.png')}}"  class="mb-3" />
                                </div>
                                <form method="post" action="{{route('UserLogin' , [$restaurant->id , $branch->id])}}" id="post-form">
                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                    <div class="input-style input-style-1 form-group">
                                        <label class="font-14 font-600">@lang('messages.phone_number')</label>
                                        <input class="form-control" type="number" name="phone_number"  placeholder="@lang('messages.phone_number')">
                                        @if ($errors->has('phone_number'))
                                            <span>
                                                    <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                    <div class="input-style input-style-1">
                                        <label class="font-14 font-600">@lang('messages.password')</label>
                                        <input class="form-control" type="password" name="password" placeholder="@lang('messages.password')">
                                    </div>
                                    <div class="d-flex">

                                        <p class="ml-auto font-12 font-600 mb-0 ">
                                            <a class="font-600 pointer" href="{{route('show_user_forget_password' , $restaurant->id)}}">@lang('messages.forget_password')</a>
                                        </p>
                                        <p class="mr-auto font-12 font-600 mb-0 ">
                                            <a class="font-600 pointer" href="{{route('show_user_register' , $restaurant->id)}}">@lang('messages.registerUserAccount')</a>
                                        </p>
                                    </div>

                                    <button type="submit" class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-dark2-dark">@lang('messages.login')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('website.'.session('theme_path').'silver.layout.footer')

</div>
<script>
    if ($("#post-form").length > 0) {
        $("#post-form").validate({

            rules: {
                phone_number: {
                    required: true,
                    maxlength: 11,
                    // unique: true,
                },
                password: {
                    required: true,
                    minlength: 6
                }

            },
            messages: {
                phone_number: {
                    required: "{{trans('messages.phone_number')}}" +" "+ "{{trans('messages.required')}}",
                    maxlength: "{{trans('messages.max_length')}}" + " "+ "{{trans('messages.phone_number')}}" + "11",
                },
                password: {
                    required: "{{trans('messages.password')}}" +" "+ "{{trans('messages.required')}}",
                    minlength: "{{trans('messages.min_length')}}" + " "+ "{{trans('messages.password')}}" + "6",
                },
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var formData = new FormData($(this)[0]);

                $('#send_form').html('Sending..');
                $.ajax({
                    url: "{{ route('UserLogin' , $restaurant->id) }}" ,
                    type: "POST",
                    data: $('#post-form').serialize(),
                    success: function( response ) {
                        if(response.errors && response.errors.length > 0)
                        {
                            jQuery.each(response.errors, function(key, value){
                                jQuery('.alert-danger').show();
                                jQuery('.alert-danger').append('<p>'+value+'</p>');
                            });
                        }else{
                            $('#send_form').html('Submit');
                            $('#res_message').show();
                            $('#res_message').html(response.msg);
                            $('#msg_div').removeClass('d-none');

                            document.getElementById("post-form").reset();
                            setTimeout(function(){
                                $('#res_message').hide();
                                $('#msg_div').hide();
                            },10000);
                            window.location=response.url;
                        }
                    }
                });
            }
        })
    }
</script>

<!-- footer and footer card-->
<!-- end of page content-->
@include('website.'.session('theme_path').'silver.layout.scripts')
