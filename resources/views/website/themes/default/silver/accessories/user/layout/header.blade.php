
<!doctype html>
<html lang="ZXX">
<head>
    <title> @lang('messages.appName') </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{asset('img/logo.png')}}" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('Auth_css/bootstrap.min.css')}}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Tajawal:500&display=swap" rel="stylesheet">
    <!-- Meanmenu css -->
    <link rel="stylesheet" href="{{asset('Auth_css/meanmenu.css')}}">
    <!-- Magnific css -->
    <link rel="stylesheet" href="{{asset('Auth_css/magnific-popup.min.css')}}">
    <!-- Animation CSS -->
    <link href="{{asset('Auth_css/aos.min.css')}}" rel="stylesheet">
    <!-- Slick Carousel CSS -->
    <link href="{{asset('Auth_css/slick.css')}}" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <link rel="stylesheet" href="{{asset('Auth_css/responsive.css')}}">

    @yield('styles')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <style>
        .error{
            color:red;
            display: block !important;
        }
        label {
            display: block;
            width: 100%;
            font-size: 15px;
        }
        #map {
            height: 350px;
            width: 900px;
        }
    </style>
</head>
<body dir="rtl">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!--  Preloader Start
========================-->
<div id='preloader'>
    <div id='status'>
        <img src='{{asset('Auth_images/loading.gif')}}' alt='LOADING...!' />
    </div>
</div>

<!--=========== Main Header area ===============-->
<header id="home" >
    <div id="top-bar">
        <div id="top-bar-inner" class="container">
            <div class="top-bar-inner-wrap">
                <div class="top-bar-content">
                    <div class="inner">

                        <span class="phone content"> 000000000 <i class="fa fa-phone"></i></span>
                        <span class="email content">  info@site.com <i class="far fa-envelope"></i></span>



                    </div>
                </div><!-- /.top-bar-content -->
                <div class="top-bar-socials">
                    <div class="inner">
                        <span class="email content">  English <i class="fa fa-language"></i></span>
                    </div>
                </div><!-- /.top-bar-socials -->

            </div>
        </div>
    </div><!-- /#top-bar -->
    <div class="main-navigation">
        <div class="container">
            <div class="row">
                <!-- logo-area-->
                <div class="col-xl-3 col-lg-4 col-md-4">
                    <div class="logo-area">
                        <a href="index.html"><img src="{{asset('Auth_images/logo.png')}}" alt="enventer"></a>
                    </div>
                </div>
                <!-- mainmenu-area-->
                <div class="col-xl-9 col-lg-8 col-md-8">
                    <div class="main-menu f-left">
                        <nav id="mobile-menu">
                            <ul>
                                <li>
                                    <a class="current" href="#home">الرئيسية</a>
                                </li>
                                <li>
                                    <a href="#aboutus">الباقات   </a>
                                </li>
                                <li>
                                    <a href="#features" >المميزات </a>

                                <li>
                                    <a href="#contactus">  اتصل بنا</a>
                                </li>
                                <li>
                            </ul>
                        </nav>
                    </div>
                    <!-- mobile menu-->
                    <div class="mobile-menu"></div>
                    <!--Search-->
                    <div class="search-box-area">

                        <div class="search-icon-area">
                            <div class=" aos-init aos-animate" data-aos="fade-in" data-aos-anchor-placement="top-bottom" data-aos-delay="1500" data-aos-duration="1200">
                                <a href="#" class="skill-btn"> تسجيل دخول  <i class="fa fa-user"></i></a>
                                <a href="#" class="skill-btn skill-btn2"> تسجيل عضوية   <i class="fa fa-user"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>

