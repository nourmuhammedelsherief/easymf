<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item start active open">
                <a href="/admin/home" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">الرئيسية</span>
                    <span class="selected"></span>

                </a>
            </li>
            <li class="heading">
                <h3 class="uppercase">القائمة الجانبية</h3>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admins') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users" style="color: aqua;"></i>
                    <span class="title">المشرفين</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins') }}" class="nav-link ">
                            <span class="title">عرض المشرفين</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins/create') }}" class="nav-link ">
                            <span class="title">اضافة مشرف</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'users') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users" style="color: aqua;"></i>
                    <span class="title">المستخدمين</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('User' , 'origin') }}" class="nav-link ">
                            <span class="title"> أصحاب المنشاءات </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('User' , 'sector') }}" class="nav-link ">
                            <span class="title"> المهتمين بالقطاع </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('createUser') }}" class="nav-link ">
                            <span class="title">اضافة مستخدم جديد</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admin/countries') !== false ? 'active' : '' }}">
                <a href="{{route('countries.index')}}" class="nav-link ">
                    <i style="color: #0f74a8" class="fa fa-flag"></i>
                    <span class="title"> @lang('messages.countries') </span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/cities') !== false ? 'active' : '' }}">
                <a href="{{route('cities.index')}}" class="nav-link ">
                    <i style="color: #0f74a8" class="fa fa-flag"></i>
                    <span class="title"> @lang('messages.cities') </span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>

{{--            $2y$10$EN9xfVoJsj9xicGGFdY9g.hrQCvDxRf43iHExP9ONTlOerOIWoLb2--}}


{{--            <li class="nav-item {{ strpos(URL::current(), 'admin/settings') !== false ? 'active' : '' }}">--}}
{{--                <a href="{{route('Setting')}}" class="nav-link ">--}}
{{--                    <i style="color: #0f74a8" class="fa fa-cog"></i>--}}
{{--                    <span class="title"> أعدادات الموقع </span>--}}
{{--                    <span class="pull-right-container">--}}
{{--            </span>--}}

{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="nav-item {{ strpos(URL::current(), '/admin/about_us') !== false ? 'active' : '' }}">--}}
{{--                <a href="javascript:;" class="nav-link nav-toggle">--}}
{{--                    <i class="fa fa-pagelines" style="color: aqua;"></i>--}}
{{--                    <span class="title"> الصفحات </span>--}}
{{--                    <span class="arrow"></span>--}}
{{--                </a>--}}
{{--                <ul class="sub-menu">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('AboutUs') }}" class="nav-link ">--}}
{{--                            <span class="title"> من نحن  </span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('principle') }}" class="nav-link ">--}}
{{--                            <span class="title"> المبادئ  </span>--}}
{{--                        </a>--}}
{{--                    </li>--}}


{{--                </ul>--}}
{{--            </li>--}}

        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
