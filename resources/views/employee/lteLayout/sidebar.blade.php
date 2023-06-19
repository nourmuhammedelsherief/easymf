<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('employee.home')}}" class="brand-link">
        <img src="{{asset('/uploads/img/logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light"> @lang('messages.control_panel') </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(Auth::guard('employee')->user()->logo != null)
                    <img src="{{asset('/uploads/restaurants/logo/' . Auth::guard('employee')->user()->logo)}}"
                         class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="javascript:;" class="d-block">
                    <?php if (Auth::guard('employee')->check()) {
                        echo Auth::guard('employee')->user()->name;
                    } ?>
                </a>
            </div>
        </div>

    @php
        $employee = auth('employee')->user();
    @endphp
    <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(checkOrderService($employee->restaurant_id , 10))
                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ strpos(URL::current(), '/casher/delivery/orders') !== false ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                @lang('messages.delivery')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/casher/delivery/orders/new') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/delivery/orders/new') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'new')
                                    ->where('type' , 'delivery')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.new')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/delivery/orders/active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/delivery/orders/active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'active')
                                    ->where('type' , 'delivery')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/delivery/orders/completed') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/delivery/orders/completed') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'completed')
                                    ->where('type' , 'delivery')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.completed')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/delivery/orders/canceled') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/delivery/orders/canceled') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'canceled')
                                    ->where('type' , 'delivery')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.canceled')
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if(checkOrderService($employee->restaurant_id , 10))
                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ strpos(URL::current(), '/casher/takeaway/orders') !== false ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                @lang('messages.takeaway')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/casher/takeaway/orders/new') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/takeaway/orders/new') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'new')
                                    ->where('type' , 'takeaway')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.new')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/takeaway/orders/active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/takeaway/orders/active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'active')
                                    ->where('type' , 'takeaway')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/takeaway/orders/completed') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/takeaway/orders/completed') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'completed')
                                    ->where('type' , 'takeaway')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.completed')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/takeaway/orders/canceled') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/takeaway/orders/canceled') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'canceled')
                                    ->where('type' , 'takeaway')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.canceled')
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if(checkOrderService($employee->restaurant_id , 10))
                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ strpos(URL::current(), '/casher/previous/orders') !== false ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                @lang('messages.previous_orders')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/casher/previous/orders/new') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/previous/orders/new') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'new')
                                    ->where('type' , 'previous')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.new')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/previous/orders/active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/previous/orders/active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'active')
                                    ->where('type' , 'previous')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/previous/orders/completed') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/previous/orders/completed') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'completed')
                                    ->where('type' , 'previous')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.completed')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/previous/orders/canceled') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/previous/orders/canceled') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\Order::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'canceled')
                                    ->where('type' , 'previous')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.canceled')
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if(checkOrderService($employee->restaurant_id , 10))
                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ strpos(URL::current(), '/casher/tables/orders') !== false ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                @lang('messages.table_orders')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/casher/tables/orders/new') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/tables/orders/new') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\TableOrder::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'new')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.new')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/tables/orders/in_reservation') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/tables/orders/in_reservation') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\TableOrder::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'in_reservation')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.on_table')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/tables/orders/active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/tables/orders/active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\TableOrder::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'active')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/tables/orders/completed') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/tables/orders/completed') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\TableOrder::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'completed')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.completed')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/casher/tables/orders/canceled') }}"
                                   class="nav-link {{ strpos(URL::current(), '/casher/tables/orders/canceled') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                {{\App\Models\TableOrder::whereRestaurantId(Auth::guard('employee')->user()->restaurant->id)
                                    ->where('branch_id' , Auth::guard('employee')->user()->branch->id)
                                    ->where('status' , 'canceled')
                                    ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.canceled')
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
