@include('layouts.header')
<!--[if lt IE 9]>
<div class="bg-danger text-center">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" class="highlight">upgrade your browser</a> to improve your experience.</div>
<![endif]-->
<div class="preloader">
    <div class="preloader_image"></div>
</div>
<!-- search modal -->


<!-- wrappers for visual page editor and boxed version of template -->
<div id="canvas">
    <div id="box_wrapper">
        <!-- template sections -->

        @include('layouts.nav')
        <div class="container" style="margin-bottom: 15px; text-align: center;">
{{--            <img src="https://k.nooncdn.com/cms/pages/20220307/bc655ca871ac25af839963f277ef21e6/ar_slider-01.png" alt="">--}}
        </div>
        @yield('content')
        @include('layouts.footer')
