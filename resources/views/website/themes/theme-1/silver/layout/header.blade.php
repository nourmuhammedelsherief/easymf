@php
    if(isset($restaurant->id)){
        $pageTitle = app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en;
        $pageIcon = asset('uploads/restaurants/logo/' . $restaurant->logo);
    }
@endphp
<!DOCTYPE HTML>
<html lang="ar" style="background: #f5f5f5;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover"/>
    <title>{{(isset($pageTitle) and strlen($pageTitle) > 1) ? $pageTitle : trans('messages.appName')}} </title>
    <meta name="_token" content="{{ csrf_token() }}" />
    @if(isset($pageIcon) and !empty($pageIcon))
        <link rel="icon" href="{{ $pageIcon }}" type="image/x-icon">
        <link rel="icon" href="{{ $pageIcon }}" type="image/png">
        
    @else
        <link rel="icon" href="{{ URL::asset('/uploads/img/logo.png') }}" type="image/x-icon">
    @endif
    
    <link rel="stylesheet" type="text/css" href="{{asset('themes-assets/theme-1/styles/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('themes-assets/theme-1/styles/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('themes-assets/theme-1/styles/style.css')}}">
    @if(app()->getLocale() == 'en')
    <link rel="stylesheet" type="text/css" href="{{asset('themes-assets/theme-1/styles/style_ltr.css')}}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{asset('themes-assets/theme-1/styles/global.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/vanilla-calendar/vanilla-calendar.min.css')}}">
    
    <script type="text/javascript" src="{{asset('themes-assets/theme-1/scripts/jquery.js')}}"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-43CB1SDSGL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-43CB1SDSGL');
    </script>
    @stack('styles')
    
    <style>
        :root {
            --rest-background-color : {{!empty($restaurant->color) ? $restaurant->color->background : '#FFF'}} ;
            --rest-main-header-color : {{!empty($restaurant->color) ? $restaurant->color->main_heads : '#FFF'}} ;
        }
        
        .menu , 
        .menu .form-control , 
        .menu .sensitive .card{
            background-color: var(--rest-background-color) !important;
        }
        .menu.ad-menu{
            background-color: transparent !important;
        }
        .menu .footer-content{
            border: 0;
        }
    .lds-ripple div {
        border: 4px solid {{$restaurant->color == null ? '' : $restaurant->color->icons }} !important;
    }
    .mobile-width{
        max-width: 600px !important;
    }
    </style>
    @yield('style')
</head>

@if($restaurant != null)
    <body class="theme-light" data-highlight="yellow2" style="max-width: 1000px; width:99%; margin:auto; background-color: {{$restaurant->color == null ? '' : $restaurant->color->background}};">
@else
    <body class="theme-light" data-highlight="yellow2" >
@endif
