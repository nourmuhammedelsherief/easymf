

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">

<style>
    .error{
        color:red;
        display: block !important;
    }
    .display-none{
        display: none !important;
    }
    #send-again{
        font-size: 12px;
        color: #a3a3a3;
        cursor: default;
      
    }
    #send-again.active{
        color:#007bff;
        cursor: pointer;
        display: block;
        text-align: center;
        margin-top: 20px;
    }
</style>




{{--@include('website.'.session('theme_path').'silver.accessories.user.forget_password')--}}
{{--@include('website.'.session('theme_path').'silver.accessories.user.register')--}}




@include('website.'.session('theme_path').'silver.layout.header')

{{--<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>--}}


<!-- header and footer bar go here-->
<div class="header header-fixed header-auto-show header-logo-app">
    <div class="row">
        <div class="col-sm-9"></div>
        <div class="col-sm-3">
            <a  href="{{route('sliverHome' , $restaurant->name_barcode)}}">
                <h3>
                    <i class="fa fa-home"></i>
                </h3>
            </a>
        </div>
    </div>

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
        body{
            background-color: #FFF !important;
        }
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



    <div class="row pt-2 mb-0">
        <div class="col-12">
            @include('flash::message')
            <div class="card mx-4">
                <div class="content mb-0">
                    <div class="text-center">
                    
                        <img src="{{!empty($restaurant->logo) ? asset($restaurant->image_path) : asset('images/reg.gif') }}" style="width:90px;" class="mb-3"  />
                        <h3 class="text-center">{{ $restaurant->name }}</h3>
                    </div>
                    <div class="text-center">
                        <img src="{{asset('images/line.png')}}" class="mb-3"/>
                    </div>
                    <h3 class=" mb-3  text-center"> @lang('messages.loginx') </h3>
                    <form method="post" action="{{route('user_register' , $restaurant->id)}}" id="register-form" >
                        @csrf
                        <div class="row">
                            <label for="" style="padding-right:15px;padding-left:15px;">{{ trans('messages.phone_number') }} <span style="color: red">*</span></label>
                            <div class="col-sm-12 mobile-number-col">
                                
                                   {{-- <label class="font-14 font-600">@lang('messages.phone_number')</label> --}}
                                    <div class="country-flag">
                                        <select name="country_id" class="country">
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}" title="{{$country->flag == null ? null : asset($country->flag_path)}}" data_flag="@php
                                               
                                                if($country->id == 1) echo '01xxxxxxxx'; 
                                                elseif($country->code == 973) echo '3xxxxxxx'; 
                                                elseif($country->id == 2) echo '05xxxxxxxx'; 
                                                else echo $country->code . 'xxxxxxxx'; 
                                                
                                                @endphp">
                                                    {{$country->code}} +
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input class="form-control x-input-style" type="number" name="phone_number" pattern="[0-9]*" inputmode="numeric" 
                                           placeholder="@lang('messages.phone_number')">
                                    @if ($errors->has('phone_number'))
                                        <span class="help-block">
                                    <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                </span>
                                    @endif
                                
                            </div>
                            
                            <div class="col-md-12 display-none">
                                <label for="" style="">{{ trans('messages.code') }} <span style="color: red">*</span></label>
                                <input type="number" name="code" class="form-control x-input-style"  pattern="[0-9]*" inputmode="numeric" placeholder="{{trans('messages.code_enter')}}">
                            </div>
                        </div>

                        
                        <button type="button"
                                class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-dark2-dark send-sms step-1" style="margin:auto;" id="send-sms">
                            @lang('messages.send_code')
                        </button>

                        <a href="javascript:;" id="send-again" class="active display-none">{{ trans('messages.send_sms_again') }} <span></span></a>

                    </form>
                </div>
            </div>
        </div>

    </div>



</div>
@push('scripts')

<script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
<script>

    
    function formatState (state) {
        if (!state.id) {
            return state.text;
        }
        // console.log(state);
        // console.log(state.element.attributes.data_flag.value);
        if(state.title.length > 10){
            var image = '<img width="30" height="30" src="' +state.title +'" class="img-flag" />';
        }else var image = '<span>'+state.text+'</span>';
        var $state = $(
            image
        );
        return $state;
    };

    function formatState2 (state) {
        if (!state.id) {
            return state.text;
        }
        // console.log(state);
        // console.log(state.element.attributes.data_flag.value);
        if(state.title.length > 10){
            var image = '<img width="30" height="30" src="' +state.title +'" class="img-flag" />';
        }else var image = '<span>'+state.text+'</span>';
        var $state = $(
            image
        );
        
            console.log(state.element.attributes.data_flag.value);
            $('.mobile-number-col input[name=phone_number]').prop('placeholder' ,state.element.attributes.data_flag.value );
        
        return $state;
    };

    $(function(){
        console.log('done');
        console.log($("select.country"));
        $("select.country").select2({
            templateResult: formatState,
            templateSelection : formatState2, 
            // dir: "rtl",
            dropdownAutoWidth: true,
            dropdownParent: $('.country-flag')

        });
    });

    if ($("#register-form").length > 0) {
        $("#register-form").validate({

            rules: {
                name: {
                    required: true,
                    maxlength: 191,
                },
                country_id: {
                    required: true,
                },
                phone_number: {
                    required: true,
                    maxlength: 11,
                    unique:true,
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    minlength: 6
                }

            },
            messages: {
                name: {
                    required: "{{trans('messages.name')}}" +" "+ "{{trans('messages.required')}}",
                    maxlength: "{{trans('messages.max_length')}}" + " "+ "{{trans('messages.name')}}" + "191",
                },
                phone_number: {
                    required: "{{trans('messages.phone_number')}}" +" "+ "{{trans('messages.required')}}",
                    maxlength: "{{trans('messages.max_length')}}" + " "+ "{{trans('messages.phone_number')}}" + "11",
                },
                country_id: {
                    required: "{{trans('messages.country_id')}}" +" "+ "{{trans('messages.required')}}",
                },
                password: {
                    required: "{{trans('messages.password')}}" +" "+ "{{trans('messages.required')}}",
                    minlength: "{{trans('messages.min_length')}}" + " "+ "{{trans('messages.password')}}" + "6",
                },
                password_confirmation: {
                    required: "{{trans('messages.password_confirmation')}}" +" "+ "{{trans('messages.required')}}",
                    minlength: "{{trans('messages.min_length')}}" + " "+ "{{trans('messages.password_confirmation')}}" + "6",
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
                    url: "{{route('user_register' , $restaurant->id)}}" ,
                    type: "POST",
                    data: $('#register-form').serialize(),
                    success: function( response ) {
                        console.log(response);
                        $('#send_form').html('Submit');
                        if(response.errors && response.errors.length > 0)
                        {
                            jQuery.each(response.errors, function(key, value){
                                jQuery('.alert-danger').show();
                                jQuery('.alert-danger').append('<p>'+value+'</p>');
                            });
                        }else{
                            
                            $('#res_message').show();
                            $('#res_message').html(response.msg);
                            $('#msg_div').removeClass('d-none');

                            document.getElementById("register-form").reset();
                            setTimeout(function(){
                                $('#res_message').hide();
                                $('#msg_div').hide();
                            },10000);
                            // window.location=response.url;
                        }
                    }
                });
            }
        })
    }

   
</script>
    
    <script>
        var loaderImage = '<img src="{{asset("images/loader2.svg")}}" width="40" height="30" />';
        var checkCount = 30;
        var userId = 0;
        var restaurantId = {{$restaurant->id}};
         $(function(){
            $('#register-form').submit(function(){
                return false;
            });
            $('#send-sms').on('click' , function(){
                console.log('click');
                var button = $(this);
                button.html(loaderImage);
                var formData = new FormData($('#register-form')[0]);
                var url = button.hasClass('step-2') ?  "{{url('user/code/verify')}}/" + userId + '/' + restaurantId   : "{{route('user_register' , $restaurant->id)}}";
                console.log(url);
                $.ajax({
                    url : url   , 
                    method : 'POST' , 
                    headers: {Accept : 'application/json'} ,
                    data : formData , 
                    processData : false, 
                    contentType : false, 
                    success : function(json){
                        console.log(json);
                        if(json.status && json.status == true){
                            if(button.hasClass('step-1')){
                                button.removeClass('step-1').addClass('step-2');
                                button.html("@lang('messages.verification')");
                                $('input[name=phone_number]').prop('readonly'  , true);
                                $('input[name=code]').parent().removeClass('display-none');
                                $('#send-again').removeClass('display-none');
                                $('input[name=code]').val('');
                                userId = json.user_id;
                            }else if(button.hasClass('step-2')){
                                console.log('step2');
                                toastr.success(json.msg , "@lang('messages.verification')");
                                button.html("@lang('messages.verification')");
                                console.log(json.redirect_to);
                                setTimeout(() => {
                                    // console.log(json);
                                    window.location.replace(json.redirect_to);    
                                }, 5000);
                                    
                                
                                
                            }
                        }else if(json.status == false){
                            if(button.hasClass('step-2')){
                                console.log('step2');
                                toastr.error(json.msg , "@lang('messages.login')");
                                button.html("@lang('messages.verification')");
                                // window.location.replace(json.redirect_to);
                            }else{
                                toastr.error(json.msg , "@lang('messages.verification')");
                                button.html("@lang('messages.verification')");
                            }
                            
                        }
                        else{
                            button.html("@lang('messages.send_code')");
                        }
                    },
                    error: function(xhr){
                        console.log(xhr);
                        toastr.info("{{trans('messages.fail_to_send_login')}}" , "@lang('messages.verification')");
                        button.html("@lang('messages.send_code')");
                    } ,
                });
            });
            // send again
            $('#send-again').on('click' , function(){
                console.log('again');
                var button = $('#send-sms');
                button.html(loaderImage);
                var formData = new FormData($('#register-form')[0]);
                $.ajax({
                    url : "{{route('user_register' , $restaurant->id)}}"  , 
                    method : 'POST' , 
                    headers: {Accept : 'application/json'} ,
                    data : formData , 
                    processData : false, 
                    contentType : false, 
                    success : function(json){
                        console.log(json);
                        if(json.status && json.status == true){
                            button.removeClass('step-1').addClass('step2');
                            button.html("@lang('messages.verification')");
                            $('input[name=phone_number]').prop('disabled' , true);
                            $('input[name=code]').parent().removeClass('display-none');
                            $('#send-again').removeClass('display-none');
                            $('input[name=code]').val('');
                            userId = json.user_id;
                        }else if(json.status == false){
                            toastr.error(json.msg , "@lang('messages.verification')");
                            button.html("@lang('messages.send_code')");
                        }
                        else{
                            button.html("@lang('messages.send_code')");
                        }
                    },
                    error: function(xhr){
                        console.log(xhr);
                        toastr.info("{{trans('messages.fail_to_send_login')}}" , "@lang('messages.verification')");
                        button.html("@lang('messages.send_code')");
                    } ,
                });
            });

           
        });
    </script>
@endpush
<!-- footer and footer card-->
@include('website.'.session('theme_path').'silver.layout.footer')
<!-- end of page content-->
@include('website.'.session('theme_path').'silver.layout.scripts')

