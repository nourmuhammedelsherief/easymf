<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('marketer.home')}}" class="brand-link">
        <img src="{{asset('/uploads/img/logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light"> @lang('messages.control_panel') </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(Auth::guard('marketer')->user()->logo != null)
                    <img src="{{asset('/uploads/restaurants/logo/' . Auth::guard('marketer')->user()->logo)}}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{url('/restaurant/profile')}}" class="d-block">
                    @if(app()->getLocale() == 'ar')
                        <?php if (Auth::guard('restaurant')->check()) {
                            echo Auth::guard('restaurant')->user()->name_ar;
                        } ?>
                    @else
                        <?php if (Auth::guard('restaurant')->check()) {
                            echo Auth::guard('restaurant')->user()->name_en;
                        } ?>
                    @endif
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('marketerProfile')}}" class="nav-link {{ strpos(URL::current(), '/marketer/profile') !== false ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            @lang('messages.profile')
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('confirmed_operations')}}" class="nav-link {{ strpos(URL::current(), '/marketer/confirmed_operations') !== false ? 'active' : '' }}">
                        <i class="nav-icon fa fa-balance-scale-right"></i>
                        <p>
                            @lang('messages.confirmed_operations')
                        </p>
                    </a>
                </li>
                <!--<li class="nav-item">-->
                <!--    <a href="{{route('not_confirmed_operations')}}" class="nav-link {{ strpos(URL::current(), '/marketer/not_confirmed_operations') !== false ? 'active' : '' }}">-->
                <!--        <i class="nav-icon fa fa-balance-scale-left"></i>-->
                <!--        <p>-->
                <!--            @lang('messages.not_confirmed_operations')-->
                <!--        </p>-->
                <!--    </a>-->
                <!--</li>-->
                <li class="nav-item">
                    <a href="{{route('transfersMarketer')}}" class="nav-link {{ strpos(URL::current(), '/marketer/transfers') !== false ? 'active' : '' }}">
                        <i class="nav-icon fa fa-money-bill"></i>
                        <p>
                            @lang('messages.bank_transfers')
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
