<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link " id="btn-sidebar-menu" data-widget="pushmenux" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('employee.home')}}" class="nav-link">@lang('messages.control_panel')</a>
        </li>
        {{--        <li class="nav-item d-none d-sm-inline-block">--}}
        {{--            <a href="#" class="nav-link">Contact</a>--}}
        {{--        </li>--}}
        <li class="nav-item d-none d-sm-inline-block">
            {{--            <a href="#" class="nav-link">Lang</a>--}}
            {{--            @if(\Illuminate\Support\Facades\Auth::guard('employee')->user()->ar == 'true')--}}
            {{--                {{session()->put('locale', 'ar')}}--}}
            {{--            @elseif(\Illuminate\Support\Facades\Auth::guard('employee')->user()->en == 'true')--}}
            {{--                {{session()->put('locale', 'en')}}--}}
            {{--            @endif--}}
            @if(app()->getLocale() == 'en')
                <a href="{{ url('locale/ar')  }}" class="nav-link">
                    <span class="username username-hide-on-mobile">
                        <i class="fa fa-language"></i>
                        عربى
                    </span>
                </a>

            @else
                <a href="{{  url('locale/en') }}" class="nav-link">
                    <span class="username username-hide-on-mobile">
                        <i class="fa fa-language"></i>
                        English
                    </span>
                </a>
            @endif
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto-navbav">

        <li class="nav-item ">
            <a target="_blank" class="nav-link btn btn-success"  href="https://api.whatsapp.com/send?phone=966590136653">
                <i class="fa fa-whatsapp"></i>
                <span class="hidemob">
                    @lang('messages.technical_support')
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <?php if (Auth::guard('employee')->check()) {
                    echo Auth::guard('employee')->user()->name;
                } ?>
                <span class="badge badge-warning navbar-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"> @lang('messages.profile') </span>
                <div class="dropdown-divider"></div>
                <a href="{{url('/employee/profile')}}" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    @lang('messages.profile')
                </a>
                <div class="dropdown-divider"></div>
                <a onclick="document.getElementById('logout_form').submit()" class="dropdown-item">
                    <i class="fas fa-key"></i>
                    @lang('messages.logout')
                </a>
            </div>
        </li>
        {{--        <li class="nav-item">--}}
        {{--            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">--}}
        {{--                <i class="fas fa-th-large"></i>--}}
        {{--            </a>--}}
        {{--        </li>--}}
    </ul>
    <form style="display: none;" id="logout_form" action="{{ route('employee.logout') }}" method="post">
        {!! csrf_field() !!}
    </form>
</nav>
<!-- /.navbar -->
