<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">


    <!-- Brand Logo -->
    <a href="{{url('/admin/home')}}" class="brand-link">
        <img src="{{asset('/uploads/img/logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">@lang('messages.control_panel')</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{url('/admin/home')}}" class="d-block">
                    <?php if (Auth::guard('admin')->check()) {
                        echo Auth::guard('admin')->user()->name;
                    } ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <?php $admin = Auth::guard('admin')->user(); ?>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if($admin->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.service.index') }}"
                           class="nav-link {{ isUrlActive('our_service') ? 'active' : '' }}">
                            <i class="fas fa-concierge-bell nav-icon"></i>
                            <p>
                                @lang('dashboard.our_services')
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.service_category.index') }}"
                           class="nav-link {{ isUrlActive('service_category') ? 'active' : '' }}">
                            <i class="fas fa-concierge-bell nav-icon"></i>
                            <p>
                                @lang('messages.service_categories')
                            </p>
                        </a>
                    </li>
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <hr style="height:3px;border-width:0;color:white;background-color:white">
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link {{ strpos(URL::current(), 'admins') !== false ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                @lang('messages.admins')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/admin/admins') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/admins') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        @lang('messages.admins')
                                    </p>
                                </a>
                            </li>
                            {{--                        <li class="nav-item">--}}
                            {{--                            <a href="{{ url('/admin/admins/create') }}"--}}
                            {{--                               class="nav-link {{ strpos(URL::current(), '/admin/admins/create') !== false ? 'active' : '' }}">--}}
                            {{--                                <i class="far fa-circle nav-icon"></i>--}}
                            {{--                                <p>--}}
                            {{--                                    @lang('messages.add_admin')--}}
                            {{--                                </p>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('tasks.my') }}"
                       class="nav-link {{ isUrlActive('n/my-tasks') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <p>
                            @lang('dashboard.my_tasks') <span class="badge badge-info right">
                                {{$restaurants = \App\Models\Task::where('type' , 'task')->where('status' , 'in_progress')->where('employee_id' , auth('admin')->id())->count()}}
                            </span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin_details.my') }}"
                       class="nav-link {{ isUrlActive('profile/my-tasks') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <p>
                            @lang('dashboard.my_admin_details')
                            <span class="badge badge-info right">
                                {{$restaurants = \App\Models\AdminDetail::where('employee_id' , auth('admin')->id())->count()}}
                            </span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('my-notes.index') }}"
                       class="nav-link {{ isUrlActive('my-notes') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <p>
                            @lang('dashboard.my-notes')
                            <span class="badge badge-info right">
                                {{$restaurants = \App\Models\AdminMemo::where('employee_id' , auth('admin')->id())->count()}}
                            </span>
                        </p>
                    </a>
                </li>
                <hr style="height:4px;border-width:0;background-color:white">
                @if($admin->role == 'admin' or $admin->role == 'sales')
                    <li class="nav-item">
                        <a href="{{ url('/admin/restaurants/InActive') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/restaurants/InActive') !== false ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                <span class="badge badge-info right">
                                    {{$restaurants = \App\Models\Restaurant::where('admin_activation' , 'false')->whereNotIn('status' , ['inComplete'])->count()}}
                                </span>
                                @lang('messages.restaurants') @lang('messages.restaurantsInActive')
                            </p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ (
                        isUrlActive('restaurants/tentative_active') or
                        isUrlActive('restaurants/tentative_finished') or
                        isUrlActive('restaurants/active') or
                        isUrlActive('restaurants/less_30_day') or
                        isUrlActive('restaurants/finished')
                    ) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                @lang('messages.restaurants')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('/admin/restaurants/tentative_active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/restaurants/tentative_active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$restaurants = \App\Models\Restaurant::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'tentative');
                                            $q->where('type' , 'restaurant');
                                            $q->where('package_id' , 1);
                                        })
                                        ->where('status' , 'tentative')
                                        ->where('admin_activation' , 'true')
                                        ->where('archive' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.tentative_active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/restaurants/tentative_finished') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/restaurants/tentative_finished') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$restaurants = \App\Models\Restaurant::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'tentative_finished');
                                            $q->where('type' , 'restaurant');
                                            $q->where('package_id' , 1);
                                        })
                                        ->where('status' , 'tentative')
                                        ->where('admin_activation' , 'true')
                                        ->where('archive' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.tentative_finished')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/restaurants/active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/restaurants/active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$restaurants = \App\Models\Restaurant::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'active');
                                            $q->where('type' , 'restaurant');
                                            $q->where('package_id' , 1);
                                            $q->whereDate('end_at', '>=', now()->addDays(30));
                                        })
                                        ->where('status' , 'active')
                                        ->where('archive' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/restaurants/less_30_day') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/restaurants/less_30_day') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$restaurants = \App\Models\Restaurant::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'active');
                                            $q->where('type' , 'restaurant');
                                            $q->where('package_id' , 1);
                        //                    $q->where('end_at', '>', now()->subDays(30));
                                            $q->whereDate('end_at', '<=', now()->addDays(30));
                                        })
                                        ->where('status' , 'active')
                                        ->where('archive' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.less_30_day')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/restaurants/finished') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/restaurants/finished') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$restaurants = \App\Models\Restaurant::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'finished');
                                            $q->where('type' , 'restaurant');
                                            $q->where('package_id' , 1);
                                        })
                                        ->where('status' , 'finished')
                                        ->where('archive' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.finished')
                                    </p>
                                </a>
                            </li>


                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('/admin/create/restaurants') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/create/restaurants') !== false ? 'active' : '' }}">
                            <i class="fas fa-plus nav-icon"></i>
                            <p>
                                @lang('messages.create_restaurant')
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/restaurants/inComplete') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/restaurants/inComplete') !== false ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <span class="badge badge-info right">
                            {{$restaurants = \App\Models\Restaurant::where('status' , 'inComplete')
                                ->where('archive' , 'false')->count()}}
                        </span>
                            <p>
                                @lang('messages.rest_inComplete')
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/restaurants/archived') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/restaurants/archived') !== false ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <span class="badge badge-info right">
                            {{$restaurants = \App\Models\Restaurant::where('archive' , 'true')->count()}}
                        </span>
                            <p>
                                @lang('messages.rest_archived')
                            </p>
                        </a>
                    </li>
                    <hr style="height:4px;border-width:0;background-color:white">

                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ (
                        isUrlActive('branches/active') or
                        isUrlActive('branches/less_30_day') or
                        isUrlActive('branches/finished')
                       ) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                @lang('messages.branches')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/admin/branches/tentativeA') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/branches/tentativeA') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$branches = \App\Models\Branch::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'tentative');
                                        })
                                        ->where('status' , 'tentative')
                                        ->where('archive' , 'false')
                                        ->where('main' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.tentative_active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/branches/tentative_finished') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/branches/tentative_finished') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$branches = \App\Models\Branch::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'tentative_finished');
                                        })
                                        ->where('status' , 'tentative_finished')
                                        ->where('archive' , 'false')
                                        ->where('main' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.tentative_finished')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/branches/active') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/branches/active') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$branches = \App\Models\Branch::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'active');
                                            $q->whereDate('end_at', '>=', now()->addDays(30));
                                        })
                                        ->where('status' , 'active')
                                        ->where('archive' , 'false')
                                        ->where('main' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.active')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/branches/less_30_day') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/branches/less_30_day') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$branches = \App\Models\Branch::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'active');
                        //                    $q->where('end_at', '>', now()->subDays(30));
                                            $q->whereDate('end_at', '<=', now()->addDays(30));
                                        })
                                        ->where('status' , 'active')
                                        ->where('archive' , 'false')
                                        ->where('main' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.less_30_day')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/branches/finished') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/branches/finished') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{$branches = \App\Models\Branch::with('subscription')
                                        ->whereHas('subscription' , function ($q){
                                            $q->where('status' , 'finished');
                                        })
                                        ->where('status' , 'finished')
                                        ->where('main' , 'false')
                                        ->where('archive' , 'false')->count()}}
                                </span>
                                    <p>
                                        @lang('messages.finished')
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/branches/archived') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/branches/archived') !== false ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <span class="badge badge-info right">
                            {{$branches = \App\Models\Branch::where('archive' , 'true')
                                ->where('main' , 'false')->count()}}
                        </span>
                            <p>
                                @lang('messages.branch_archived')
                            </p>
                        </a>
                    </li>
                    <li class="nav-item {{isUrlActive('branches/in_complete') ? 'active' : ''}}">
                        <a href="{{ url('/admin/branches/in_complete') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/branches/in_complete') !== false ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <span class="badge badge-info right">
                            {{$branches = \App\Models\Branch::where('status' , 'not_active')
                                ->where('main' , 'false')->count()}}
                        </span>
                            <p>
                                @lang('messages.branch_inComplete')
                            </p>
                        </a>
                    </li>
                @endif
                <hr style="height:4px;border-width:0;background-color:white">

                @if($admin->role == 'admin')
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link {{ strpos(URL::current(), 'reports') !== false ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                الإحصائيات
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/admin/reports') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/reports') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        التقارير
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/cities_reports') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/cities_reports') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        تقارير المدن
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/category_reports') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/category_reports') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        تقارير الأقسام
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <hr style="height:4px;border-width:0;background-color:white">

                    {{-- tasks --}}
                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ (strpos(URL::current(), 'task_') !== false  or strpos(URL::current(), 'admin_details') !== false or strpos(URL::current(), 'tasks') !== false) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                {{ trans('dashboard.t_tasks') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('tasks.index') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/tasks') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        {{ trans('dashboard.tasks') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/task_categories') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/task_categories') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        {{ trans('dashboard.task_categories') }}
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/admin/admin_details') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/admin_details') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        {{ trans('dashboard.admin_details') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <hr style="height:4px;border-width:0;background-color:white">

                    {{-- clients --}}
                    <li class="nav-item">
                        <a href="{{ url('/admin/clients') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/clients') !== false ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <span class="badge badge-info right">
                            {{number_format(($branches = \App\Models\User::count()))}}
                        </span>
                            <p>
                                @lang('messages.clients')
                            </p>
                        </a>
                    </li>

                @endif
                @if($admin->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ url('/admin/restaurant-ads') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/restaurant-ads') !== false ? 'active' : '' }}">
                            <i class="fas fa-wifi"></i>
                            {{-- <span class="badge badge-info right">
                            {{number_format(($branches = \App\Models\ClientRequest::count()))}}
                        </span> --}}
                            <p>
                                @lang('dashboard.restaurant_ads')
                            </p>
                        </a>
                    </li>
                @endif
                @if($admin->role == 'sales' or $admin->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ url('/admin/client_request') }}"
                           class="nav-link {{ strpos(URL::current(), '/admin/client_request') !== false ? 'active' : '' }}">
                            <i class="fas fa-cog nav-icon"></i>
                            <span class="badge badge-info right">
                            {{number_format(($branches = \App\Models\ClientRequest::count()))}}
                        </span>
                            <p>
                                @lang('dashboard.client_requests')
                            </p>
                        </a>
                    </li>
                    <hr style="height:4px;border-width:0;background-color:white">

                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ strpos(URL::current(), '/admin/subscription') !== false ? 'active' : '' }}">
                            <i class="nav-icon fa fa-money-bill-alt"></i>
                            <p>
                                @lang('messages.bank_transfers')
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('subscription.confirm') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/subscription/confirm') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Subscription::where('transfer_photo' , '!=' , null)
                                        ->where('type' , 'restaurant')
                                        ->where('payment_type' , 'bank')
                                        ->whereIn('status' , ['finished' , 'tentative_finished'])
                                        ->orWhere('payment' , 'true')
                                        ->where('type' , 'restaurant')
                                        ->where('payment_type' , 'bank')
                                        ->where('transfer_photo' , '!=' , null)
                                        ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.restaurantTransfer')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('subscription.confirm_branch') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/subscription/branches') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Subscription::where('transfer_photo' , '!=' , null)
                                        ->where('type' , 'branch')
                                        ->where('status' , '!=' , 'active')
                                        ->where('payment_type' , 'bank')
                                        ->orWhere('payment' , 'true')
                                        ->where('type' , 'branch')
                                        ->where('payment_type' , 'bank')
                                        ->where('transfer_photo' , '!=' , null)
                                     ->count()}}
                                </span>
                                    <p>
                                        @lang('messages.branchTransfer')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.service.subscription_confirm') }}"
                                   class="nav-link {{ isUrlActive('subscription/our_services') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\ServiceSubscription::whereNull('paid_at')->where('type' , 'bank')->whereNotNull('photo')
                                     ->count()}}
                                </span>
                                    <p>
                                        {{ trans('dashboard.services_subscription') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if($admin->role == 'admin')
                    <li class="nav-item">
                        <a href="{{route('admin.histories')}}"
                           class="nav-link {{ strpos(URL::current(), 'admin/histories') !== false ? 'active' : '' }}">
                            <i class="nav-icon fa fa-history"></i>
                            <span class="badge badge-info right">
                            {{\App\Models\History::count()}}
                        </span>
                            <p>
                                @lang('messages.histories')
                            </p>
                        </a>
                    </li>
                    @php
                        $isOpen = (
                        isUrlActive('settings') or
                        isUrlActive('banks') or
                        isUrlActive('countries') or
                        isUrlActive('dmin/categories') or
                        isUrlActive('marketers') or
                        isUrlActive('packages')

                    ) ? true : false;
                    @endphp
                    <hr style="height:4px;border-width:0;background-color:white">

                    <li class="nav-item has-treeview menu-open">
                        <a href="#"
                           class="nav-link {{ $isOpen !== false ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                @lang('messages.settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/admin/settings') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/settings') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        @lang('messages.public_settings')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/admin/banks') }}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/banks') !== false ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        @lang('messages.banks')
                                    </p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{route('countries.index')}}"
                                   class="nav-link {{ strpos(URL::current(), '/admin/countries') !== false ? 'active' : '' }}">
                                    {{-- <i class="far fa-circle nav-icon"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Country::count()}}
                                </span>
                                    <p>
                                        @lang('messages.countries')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('categories.index')}}"
                                   class="nav-link {{ strpos(URL::current(), 'admin/categories') !== false ? 'active' : '' }}">
                                    {{-- <i class="nav-icon fa fa-sliders-h"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Category::count()}}
                                </span>
                                    <p>
                                        @lang('messages.categories')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('archive-categories.index')}}"
                                   class="nav-link {{ strpos(URL::current(), 'admin/archive-categories') !== false ? 'active' : '' }}">
                                    {{-- <i class="nav-icon fa fa-sliders-h"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\ArchiveCategory::count()}}
                                </span>
                                    <p>
                                        @lang('messages.archive_categories')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('packages.index')}}"
                                   class="nav-link {{ strpos(URL::current(), 'admin/packages') !== false ? 'active' : '' }}">
                                    {{-- <i class="nav-icon fa fa-chart-area"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Package::count()}}
                                </span>
                                    <p>
                                        @lang('messages.packages')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('marketers.index')}}"
                                   class="nav-link {{ strpos(URL::current(), 'admin/marketers') !== false ? 'active' : '' }}">
                                    {{-- <i class="nav-icon fa fa-marker"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Marketer::count()}}
                                </span>
                                    <p>
                                        @lang('messages.marketers')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('answers.index')}}"
                                   class="nav-link {{ strpos(URL::current(), 'admin/answers') !== false ? 'active' : '' }}">
                                    {{-- <i class="nav-icon fa fa-marker"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\RegisterAnswers::count()}}
                                </span>
                                    <p>
                                        @lang('messages.register_questions')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('public_questions.index')}}"
                                   class="nav-link {{ strpos(URL::current(), 'admin/public_questions') !== false ? 'active' : '' }}">
                                    {{-- <i class="nav-icon fa fa-marker"></i> --}}
                                    <i class="far fa-circle nav-icon"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\PublicQuestion::count()}}
                                </span>
                                    <p>
                                        @lang('messages.public_questions')
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
