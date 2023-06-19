<header class="page_header header_white toggler_right">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 display_table">
                <div class="header_left_logo display_table_cell"> <a href="./" class="logo logo_with_text">
                        <img src="{{asset('img/logo.webp')}}" alt="">

                    </a> </div>
                <div class="header_mainmenu display_table_cell text-right">


                    <!-- main nav start -->
                    <nav class="mainmenu_wrapper">
                        <ul class="mainmenu nav sf-menu">
                            <li class="{{ strpos(URL::current(), '/') !== false ? 'active' : '' }}">
                                <a href="{{url('/')}}">الرئيسية</a>
                            </li>
                            <li class="{{ strpos(URL::current(), '/downloads') !== false ? 'active' : '' }}">
                                <a href="#">التنزيلات</a>
                                <ul>
                                    <?php $downloads = \App\Models\Download::all();  ?>
                                    @if($downloads->count() > 0)
                                        @foreach($downloads as $download)
                                            <li>
                                                <a href="{{route('Download' , $download->id)}}">
                                                    {{$download->name}}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                            <li class="{{ strpos(URL::current(), '/diwans') !== false ? 'active' : '' }}">
                                <a href="{{route('WebSiteDiwan')}}">الديوان</a>
                            </li>
                            <li class="{{ strpos(URL::current(), '/media') !== false ? 'active' : '' }}">
                                <a href="{{route('Media')}}">المحتوى المرئي</a>
                            </li>
                            <li class="{{ strpos(URL::current(), '/sounds') !== false ? 'active' : '' }}">
                                <a href="{{route('Sounds')}}">صوتيات</a>
                            </li>
                            <li class="{{ strpos(URL::current(), '/links') !== false ? 'active' : '' }}">
                                <a href="{{route('Links')}}">روابط مهمة</a>
                            </li>
                            <li class="{{ strpos(URL::current(), '/services') !== false ? 'active' : '' }}">
                                <a href="{{route('Services')}}">الخدمات المدفوعة</a>
                            </li>
                            <li class="{{ strpos(URL::current(), '/contacts') !== false ? 'active' : '' }}">
                                <a href="{{route('Contact')}}">التواصل</a>
                            </li>
                            @guest
                                <li>
                                    <div class="dropdown">
                                        <a href="#" class="theme_button min_width_button color2" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-lock rightpadding_5"></i>  التسجيل</a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{route('userLogin')}}"> تسجيل دخول</a>
                                            <a class="dropdown-item" href="{{route('userRegister')}}"> تسجيل جديد</a>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li>
                                    <div class="dropdown">
                                        <a href="#" class="theme_button min_width_button color2" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->name }}
                                        </a>
{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a class="dropdown-item" href="{{url('/admin/login')}}">لوحة التحكم</a>--}}
{{--                                            <a class="dropdown-item" href="{{route('userLogin')}}"> تسجيل دخول</a>--}}
{{--                                            <a class="dropdown-item" href="{{route('userRegister')}}"> تسجيل جديد</a>--}}
{{--                                        </div>--}}
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('Profile') }}">
                                                الملف الشخصي
                                            </a>
                                            <a class="dropdown-item" href="{{ route('user_logout') }}"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                تسجيل الخروج
                                            </a>

                                            <form id="logout-form" action="{{ route('user_logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>

                                    </div>

                                </li>
                            @endguest

                        </ul>


                        <!-- eof main nav -->
                        <!-- header toggler --><span class="toggle_menu"><span></span></span>
                </div>
            </div>
        </div>
    </div>
</header>
