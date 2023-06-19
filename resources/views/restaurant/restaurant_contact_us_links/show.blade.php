@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.link_contact_us')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.link_contact_us')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')

    <section class="content">
        <div class="row">
            <div class="col-12">
               
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <form role="form" id="post-form" action="{{route('restaurant.contact_us.setting')}}" method="post"
                            enctype="multipart/form-data">
                          <input type='hidden' name='_token' value='{{Session::token()}}'>
                          
                          <div class="card-body">

                            <div class="form-group" style="margin-bottom:30px;">

                                <div id="barcode-svg" style="width: 240px;
                                height: 274px;
                                margin: auto;
                                padding: 20px;">
                                    <?php $name = $restaurant->name_barcode == null ? $restaurant->name_en : $restaurant->name_barcode ?>
                                    {!! QrCode::size(200)->generate(url('/restaurants/' . $name . '/contact_us' . (isset($contact->id) ? '/' . $contact->barcode : '' ) )) !!}
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

                                <h3 class="text-center" style="margin-top:10px;">
                                    <a href="#" id="printPage" class="printPage btn btn-info">@lang('messages.downloadQr')</a>
                                    <a href="{{url('/restaurants/' . $name . '/contact_us' . (isset($contact->id) ? '/' . $contact->barcode : '' ) )}}" target="__blank" id="" class=" btn btn-primary">@lang('messages.view_barcode')</a>
                                    {{--                            <a class="btn btn-primary" href="{{ URL::to('/hotel/create_pdf') }}"> @lang('messages.saveAsPdf')</a>--}}
                                </h3>


                            </div>
                            
                          <!-- /.card-body -->


                      </form>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
    

    <!-- /.row -->
    </section>
@endsection

@section('scripts')

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script src="{{asset('dist/js/html2canvas.min.js')}}"></script>
   
    <script>
        $( document ).ready(function () {
            document.getElementById("printPage").addEventListener("click", function() {
                    html2canvas(document.getElementById("barcode-svg")).then(function (canvas) {			var anchorTag = document.createElement("a");
                            document.body.appendChild(anchorTag);
                            // document.getElementById("previewImg").appendChild(canvas);
                            anchorTag.download = "{{$name}} Contact Us Page.jpg";
                            anchorTag.href = canvas.toDataURL();
                            anchorTag.target = '_blank';
                            anchorTag.click();
                        });
                });

        });
    </script>
@endsection

