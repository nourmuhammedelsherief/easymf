@extends('restaurant.lteLayout.master')
@section('title')
    @lang('messages.barcode')
@endsection
@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <style>
        #barcode-svg{
            width: 245px;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.barcode')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a></li>
                        <li class="breadcrumb-item active"> @lang('messages.barcode') </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
            <div class="row">
                <div class="col-lg-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <?php $name = $model->name_barcode == null ? $model->name_en : $model->name_barcode ?>
                            <div class="form-group">
                                <h3 class="text-center">
                                    <a href="#" id="printPage" class="printPage btn btn-info">@lang('messages.downloadQr')</a>
                                    <a href="{{url('/restaurants/' . $name)}}" id="" class=" btn btn-primary" target="__blank">@lang('messages.view_barcode')</a>
                                    {{--                            <a class="btn btn-primary" href="{{ URL::to('/hotel/create_pdf') }}"> @lang('messages.saveAsPdf')</a>--}}
                                </h3>
                                <div class="card">
                                    <div class="card-header">

                                    </div>
                                    <div class="card-body" id="barcode-svg">

                                        
                                        {!! QrCode::size(200)->generate(url('/restaurants/' . $name)) !!}
                                        <div  class="description" style="margin-top:10px;"> 
                                            <img width="20px" height="20px" src="{{asset('uploads/img/logo.png')}}" >
                                        
                                            <p class="footer-copyright pb-3 mb-1 pt-0 mt-0 font-13 font-600" style="    text-align: center;font-size:12px;display:inline; margin-right:5px;">
                                                {{trans('messages.made_love')}}
                                                <i class="fa fa-heart font-14 color-red1-dark" style="color:#000;"></i>
                                                <a style="color:#000" href="{{url('/')}}">
                                    
                                                    {{ trans('messages.at_easy_menu') }}
                                                </a>
                                            </p>
                                        </div>

                                    </div>
                                </div>

{{--                                <div class="card">--}}
{{--                                    <div class="card-header">--}}
{{--                                        <h2>--}}
{{--                                            @if(app()->getLocale() == 'ar')--}}
{{--                                                {{$model->name}}--}}
{{--                                            @else--}}
{{--                                                {{$model->en_name}}--}}
{{--                                            @endif--}}
{{--                                        </h2>--}}
{{--                                    </div>--}}
{{--                                    <div class="card-body">--}}
{{--                                        {!! QrCode::size(200)->backgroundColor(255,90,0)->generate(url('/' . $model->name_en)) !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div> <img width="50px" height="50px" src="{{asset('uploads/logo/'.\App\Setting::find(1)->logo)}}" ></div>--}}
                            </div>

                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script src="{{ URL::asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
        CKEDITOR.replace('description1');
    </script>
  <script src="{{asset('dist/js/html2canvas.min.js')}}"></script>
    <script>
        
        $(document).ready(function () {

            document.getElementById("printPage").addEventListener("click", function() {
                    html2canvas(document.getElementById("barcode-svg")).then(function (canvas) {			var anchorTag = document.createElement("a");
                            document.body.appendChild(anchorTag);
                            // document.getElementById("previewImg").appendChild(canvas);
                            anchorTag.download = "{{$name}}-barcode.jpg";
                            anchorTag.href = canvas.toDataURL();
                            anchorTag.target = '_blank';
                            anchorTag.click();
                        });
                });

            // $("a.printPage").click(function () {
            //     $("#printarea").print();
            // });
        });
    </script>
@endsection
