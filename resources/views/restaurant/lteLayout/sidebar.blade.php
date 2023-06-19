<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('restaurant.home')}}" class="brand-link">
        <img src="{{asset('/uploads/img/logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light"> @lang('messages.control_panel') </span>
    </a>
@php
    $user = Auth::guard('restaurant')->user();
@endphp
<!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if($user->logo != null)
                    <img src="{{asset('/uploads/restaurants/logo/' . $user->logo)}}"
                         class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="javascript:;" disabled class="d-block">
                    @if(app()->getLocale() == 'ar')
                        <?php if (Auth::guard('restaurant')->check()) {
                            echo $user->name_ar;
                        } ?>
                    @else
                        <?php if (Auth::guard('restaurant')->check()) {
                            echo $user->name_en;
                        } ?>
                    @endif
                </a>
            </div>
        </div>

        @if(($user->subscription != null and $user->admin_activation == 'true') or $user->type == 'employee')
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    @php
                        $account_permission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)->wherePermissionId(2)->first();
                    @endphp
                    @if($user->type == 'restaurant' or ($account_permission and $user->type == 'employee'))
                        <li class="nav-item sidebar-title">
                            <i class="nav-icon far fa-user"></i>
                            <p class="">{{ trans('dashboard.account_settings') }}</p>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('RestaurantProfile')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/profile') !== false ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    @lang('messages.profile')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/restaurant/barcode')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/barcode') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-barcode"></i>
                                <p>
                                    {{app()->getLocale() == 'ar' ? 'طباعه الباركود' : 'Print Barcode'}}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('show_restaurant_history' , $user->type == 'employee' ? $user->restaurant_id : $user->id)}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/history/' . $user->type == 'employee' ? $user->restaurant_id : $user->id) !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-history"></i>
                                <span class="badge badge-info right">
                                {{\App\Models\History::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                            </span>
                                <p>
                                    @lang('messages.histories')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('branches.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/branches') !== false ? 'active' : '' }}">
                                <i class="nav-icon far fa-flag"></i>
                                <span class="badge badge-info right">
                            {{\App\Models\Branch::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->whereIn('status' , ['active' , 'tentative'])->count()}}

                        </span>
                                <p>
                                    @lang('messages.branches')
                                </p>
                            </a>
                        </li>
                        {{--                        @if($user->type == 'restaurant')--}}

                        {{--                            <li class="nav-item">--}}
                        {{--                                <a href="{{url('/restaurant/restaurant_employees')}}"--}}
                        {{--                                   class="nav-link {{ strpos(URL::current(), '/restaurant/restaurant_employees') !== false ? 'active' : '' }}">--}}
                        {{--                                    <i class="nav-icon fa fa-users"></i>--}}
                        {{--                                    <span class="badge badge-info right">--}}
                        {{--                                        {{\App\Models\Restaurant::whereRestaurantId($user->id)->whereType('employee')->count()}}--}}
                        {{--                                    </span>--}}
                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.restaurant_employees')--}}
                        {{--                                    </p>--}}
                        {{--                                </a>--}}
                        {{--                            </li>--}}
                        {{--                        @endif--}}
                    @endif
                    @php
                        $integration_permission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)->wherePermissionId(3)->first();
                    @endphp
                    @if($user->type == 'restaurant' or ($integration_permission and $user->type == 'employee'))
                        <li class="nav-item sidebar-title">
                            <i class="nav-icon fas fa-external-link-alt"></i>
                            <p class="">{{ trans('dashboard.side_2') }}</p>
                        </li>
                        <li class="nav-item {{ strpos(URL::current(), '/restaurant/services_store') !== false ? 'active' : '' }}">
                            <a href="{{url('restaurant/services_store')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/services_store') !== false ? 'active' : '' }}">
                                <i class="fas fa-external-link-alt"></i>
                                <span class="badge badge-info right">
                                     {{\App\Models\Service::withCount('prices')->whereNotIn('type', ['bank', 'my_fatoora'])->where('status' , 'true')->count()}}
                            </span>
                                <p>
                                    @lang('dashboard.tab_3')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item {{ strpos(URL::current(), '/restaurant/integrations') !== false ? 'active' : '' }}">
                            <a href="{{url('restaurant/integrations')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/integrations') !== false ? 'active' : '' }}">
                                <i class="fas fa-external-link-alt"></i>
                                <span class="badge badge-info right">
                                {{
                                   \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->whereHas('service' , function($query){
                                    $query->whereNotIn('type' , ['bank' ,'my_fatoora']);
                                   })
                                    ->where('status' , 'active')
                                    ->count()
                                    }}
                            </span>
                                <p>
                                    @lang('dashboard.tab_2')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item {{ strpos(URL::current(), '/restaurant/integrations') !== false ? 'active' : '' }}">
                            <a href="{{url('restaurant/tentative_services')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/tentative_services') !== false ? 'active' : '' }}">
                                <i class="fas fa-external-link-alt"></i>
                                <span class="badge badge-info right">
                                {{
                                   \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->whereHas('service' , function($query){
                                    $query->whereNotIn('type' , ['bank' ,'my_fatoora']);
                                   })
                                    ->whereIn('status' , ['tentative' , 'tentative_finished'])
                                    ->count()
                                    }}
                            </span>
                                <p>
                                    @lang('dashboard.tab_2_tentative')
                                </p>
                            </a>
                        </li>
                        {{-- loyalty_points --}}
                        @php
                        $loyaltySubscription =   \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->whereHas('service' , function($query){
                                $query->where('id' , 11);
                            })
                                ->whereIn('status' , ['active' , 'tentative'])
                                ->first()
                    @endphp
                    @if(isset($loyaltySubscription->id))
                        <li class="nav-item has-treeview {{ strpos(URL::current(), 'estaurant/loyalty_point') !== false ? 'menu-open' : '' }}">
                            <a href="#"
                            class="nav-link {{ strpos(URL::current(), '/restaurant/loyalty_point') !== false ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave"></i>
                                <p>
                                    @lang('dashboard.loyalty_points')
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- prices --}}
                                <li class="nav-item">
                                    <a href="{{ url('restaurant/loyalty_point_price') }}"
                                    class="nav-link {{ strpos(URL::current(), 'loyalty_point_price') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.loyalty_point_prices')
                                        </p>
                                    </a>
                                </li>
                                {{-- settings --}}
                                <li class="nav-item">
                                    <a href="{{ url('restaurant/loyalty_point/settings') }}"
                                    class="nav-link {{ strpos(URL::current(), '/restaurant/loyalty_point/settings') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.settings')
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                        {{-- reservation --}}
                        {{--                    @if(auth('admin')->check())--}}
                        @php
                            $checkReservation = \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->where('service_id' , 1)
                            ->whereIn('status' , ['active' , 'tentative'])
                            ->first();
                        @endphp
                        @if($checkReservation)
                            <li class="nav-item has-treeview {{ (isUrlActive('reservation/branch')
                    or  isUrlActive('reservation/place')
                    or  isUrlActive('reservation/barcode')
                    or  isUrlActive('reservation/services')
                    or isUrlActive('reservation/description')
                    or isUrlActive('/reservation/tables')
                    or isUrlActive('restaurant/banks')
                    or isUrlActive('restaurant/myfatoora_token')
                    or  isUrlActive('estaurant/reservation/cash')
                    or  isUrlActive('estaurant/reservation')
                    or isUrlActive('reservation/order')   ) ? 'menu-open' : 'menu-open' }}">
                                <a href="#"
                                   class="nav-link {{ isUrlActive('reservation/branch') ? 'active' : '' }}">
                                    <i class="fas fa-comment-dots"></i>
                                    <p>
                                        @lang('dashboard.reservations')
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                        <a href="{{ route('restaurant.reservation.branch.index') }}"
                                           class="nav-link {{ isUrlActive('reservation/branch')  ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.branches')
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('restaurant.reservation.place.index') }}"
                                           class="nav-link {{ isUrlActive('reservation/place')  ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.places')
                                            </p>
                                        </a>
                                    </li>

                                    {{-- bank --}}
                                    {{-- @if(\App\Models\ServiceSubscription::whereRestaurantId($user->id)
                                    ->whereHas('service' , function($query){
                                        $query->where('type' , 'bank');
                                    })
                                    ->where('status' , 'active')
                                    ->first())
                                        <li class="nav-item">
                                            <a href="{{ url('restaurant/banks') }}"
                                               class="nav-link {{ isUrlActive('restaurant/banks')  ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    @lang('messages.transfer_bank')
                                                </p>
                                            </a>
                                        </li>
                                    @endif --}}

                                    {{-- fatoora token --}}
                                    {{-- @if(\App\Models\ServiceSubscription::whereRestaurantId($user->id)
                                    ->whereHas('service' , function($query){
                                        $query->where('type' , 'my_fatoora');
                                    })
                                    ->where('status' , 'active')
                                    ->first())
                                        <li class="nav-item">
                                            <a href="{{ url('restaurant/myfatoora_token') }}"
                                               class="nav-link {{ isUrlActive('restaurant/myfatoora_token')  ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    @lang('messages.online_payment')
                                                </p>
                                            </a>
                                        </li>
                                    @endif --}}

                                    <li class="nav-item">
                                        <a href="{{ route('restaurant.reservation.description.edit') }}"
                                           class="nav-link {{ isUrlActive('reservation/description')  ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.reservation_description')
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/restaurant/reservation/tables') }}"
                                           class="nav-link {{ isUrlActive('/reservation/tables')  ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.reservation_tables')
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('restaurant.reservation.index') }}"
                                           class="nav-link {{ isUrlActive('/reservation/order')  ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.reservation_orders')
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('resetaurant.reservation.services') }}"
                                           class="nav-link {{ ( isUrlActive('/reservation/services')
                                       or  isUrlActive('urant/banks')
                                       or  isUrlActive('estaurant/myfatoora_token')
                                       or  isUrlActive('estaurant/reservation/cash')
                                       or  isUrlActive('estaurant/reservation/service')
                                       )  ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.reservation_services')
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('reservation.settings')}}"
                                           class="nav-link {{ strpos(URL::current(), '/reservation/settings') !== false ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                {{trans('dashboard.settings')}}
                                            </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                        @php
                            $check_branch = \App\Models\Branch::with('service_subscriptions')
                                ->whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                                ->where('foodics_status' , 'true')
                                ->where('status' , 'active')
                                ->orWhereHas('service_subscriptions' , function ($q){
                                    $q->whereStatus('tentative');
                                    $q->where('service_id',4);
                                })
                                ->first();
                        @endphp
                        @if($check_branch)
                            <li class="nav-item">
                                <a href="{{route('foodics_branches')}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/foodics/branches') !== false ? 'active' : '' }}">
                                    <i class="nav-icon far fa-flag"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\RestaurantFoodicsBranch::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                                </span>
                                    <p>
                                        فروع فودكس
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('foodics_discounts' ,$check_branch->id)}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/foodics/discounts/' . $check_branch->id) !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-gift"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\FoodicsDiscount::whereBranchId($check_branch->id)->count()}}
                                </span>
                                    <p>
                                        @lang('messages.foodics_discount')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('FoodicsOrderSetting' , $check_branch->id)}}"
                                   class="nav-link {{ strpos(URL::current(), 'foodics/restaurant_setting/'.$check_branch->id) !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-cog"></i>
                                    <p>
                                        {{app()->getLocale() == 'ar' ? 'إعدادت طلبات فودكس': 'Foodics Order Setting'}}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('FoodicsOrderTable' , $check_branch->id)}}"
                                   class="nav-link {{ strpos(URL::current(), 'foodics/tables/'.$check_branch->id) !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-table"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Table::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->where('foodics_id' , '!=' , null)->count()}}
                                </span>
                                    <p>
                                        {{app()->getLocale() == 'ar' ? 'طاولات فوودكس': 'Foodics Tables'}}
                                    </p>
                                </a>
                            </li>
                            @if(auth('restaurant')->check())
                            <li class="nav-item">
                                <a href="{{route('FoodicsTableOrder' )}}"
                                   class="nav-link {{ strpos(URL::current(), 'foodics/order') !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-table"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\TableOrder::where('restaurant_id' , auth('restaurant')->id())
                                    // ->where('status' , 'in_reservation')
                                    ->whereHas('branch' , function($query){
                                        $query->where('foodics_status' , 'true');
                                    })->whereHas('table' , function($query){
                                        $query->whereNotNull('foodics_id');
                                    })->orderBy('created_at' , 'desc')->count()}}
                                </span>
                                    <p>
                                        {{ trans('dashboard.foodics_orders') }}
                                    </p>
                                </a>
                            </li>
                            @endif
                        @endif
                        @php
                            $checkWhatsAppService = \App\Models\RestaurantOrderSetting::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->where('order_type' , 'whatsapp')
                            ->where('table' , 'true')
                            ->first();
                            $checkWhatsAppSubscription = \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->whereServiceId(9)
                            ->whereIn('status',['active' , 'tentative'])
                            ->first();
                        @endphp
                        @if($checkWhatsAppService and $checkWhatsAppSubscription)
                            <li class="nav-item">
                                <a href="{{route('WhatsAppTable' , 9)}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/whatsApp/tables/9') !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-table"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Table::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->where('service_id' , 9)->count()}}
                                </span>
                                    <p>
                                        {{app()->getLocale() == 'ar' ? 'طاولات الواتساب':'WhatsApp Tables'}}
                                    </p>
                                </a>
                            </li>
                           
                        @endif
                        @if($checkWhatsAppSubscription)
                        <li class="nav-item">
                            <a href="{{route('whatsapp_branches.index' )}}"
                               class="nav-link {{ strpos(URL::current(), 'whatsapp_branches') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-table"></i>
                                <span class="badge badge-info right">
                                {{\App\Models\WhatsappBranch::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                            </span>
                                <p>
                                    {{ trans('dashboard.whatsapp_branches') }}
                                </p>
                            </a>
                        </li>
                        @endif
                        @php
                            $checkEasyMenuCasherService = \App\Models\RestaurantOrderSetting::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->where('order_type' , 'easymenu')
                            ->where('table' , 'true')
                            ->first();
                            $checkEasyMenuCasherSubscription = \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->whereServiceId(10)
                            ->whereIn('status',['active' , 'tentative'])
                            ->first();
                        @endphp
                        @if($checkEasyMenuCasherService and $checkEasyMenuCasherSubscription)
                            <li class="nav-item">
                                <a href="{{route('EasyMenuTable' , 10)}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/easymenu_casher/tables/10') !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-table"></i>
                                    <span class="badge badge-info right">
                                    {{\App\Models\Table::whereRestaurantId($user->id)->where('service_id' , 10)->count()}}
                                </span>
                                    <p>
                                        {{app()->getLocale() == 'ar' ? 'طاولات كاشير إيزي منيو':'EasyMenu Tables'}}
                                    </p>
                                </a>
                            </li>
                        @endif

                        @php
                            $checkOrderService = \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->where('service_id' , 10)
                            ->whereIn('status',['active' , 'tentative'])
                            ->first();
                        @endphp
                        @if($checkOrderService)
                            <li class="nav-item">
                                <a href="{{route('employees.index')}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/employees') !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-users"></i>
                                    <span class="badge badge-info right">
                            {{\App\Models\RestaurantEmployee::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                    <p>
                                        @lang('messages.employees')
                                    </p>
                                </a>
                            </li>
                        @endif
                        @php
                            $whatsAppService = \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->where('service_id' , 9)
                            ->whereIn('status',['active' , 'tentative'])
                            ->first();
                            $EasyMenuService = \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)
                            ->where('service_id' , 10)
                            ->whereIn('status',['active' , 'tentative'])
                            ->first();
                        @endphp
                        @if($checkOrderService or $whatsAppService or $EasyMenuService)
                            <li class="nav-item">
                                <a href="{{route('restaurant_setting.index')}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/restaurant_setting') !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-cogs"></i>
                                    <span class="badge badge-info right">
                            {{\App\Models\RestaurantOrderSetting::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                    <p>
                                        @lang('messages.restaurant_orders_settings')
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if($checkOrderService)
                            <li class="nav-item">
                                <a href="{{route('order_seller_codes.index')}}"
                                   class="nav-link {{ strpos(URL::current(), '/restaurant/order_seller_codes') !== false ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-code-branch"></i>
                                    <span class="badge badge-info right">
                            {{\App\Models\RestaurantOrderSellerCode::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                    <p>
                                        @lang('messages.order_seller_codes')
                                    </p>
                                </a>
                            </li>
                        @endif

                    @endif

                    @php
                        $menu_permission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)->wherePermissionId(4)->first();
                    @endphp
                    @if($user->type == 'restaurant' or ($menu_permission and $user->type == 'employee'))
                        <li class="nav-item sidebar-title">
                            <i class="nav-icon fa fa-bars"></i>
                            <p class="">{{ trans('dashboard.side_3') }}</p>
                        </li>


                        <li class="nav-item">
                            <a href="{{route('menu_categories.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/menu_categories') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-bars"></i>
                                <span class="badge badge-info right">
                                {{\App\Models\MenuCategory::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                            </span>
                                <p>
                                    @lang('messages.menu_categories')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('modifiers.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/modifiers') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-plus"></i>
                                <span class="badge badge-info right">
                            {{\App\Models\Modifier::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                <p>
                                    @lang('messages.modifiers')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('additions.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/additions') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-plus"></i>
                                <span class="badge badge-info right">
                            {{\App\Models\Option::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                <p>
                                    @lang('messages.options')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('posters.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/posters') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-poo-storm"></i>
                                <span class="badge badge-info right">
                            {{\App\Models\RestaurantPoster::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                <p>
                                    @lang('messages.posters')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('products.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/products') !== false ? 'active' : '' }}">
                                <i class="nav-icon fa fa-project-diagram"></i>
                                <span class="badge badge-info right">
                            {{\App\Models\Product::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->count()}}
                        </span>
                                <p>
                                    @lang('messages.products')
                                </p>
                            </a>
                        </li>
                    @endif
                    @php
                        $sales_permission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)->wherePermissionId(5)->first();
                    @endphp
                    @if($user->type == 'restaurant' or ($sales_permission and $user->type == 'employee'))
                        <li class="nav-item sidebar-title">
                            <i class="nav-icon fa fa-users"></i>
                            <p class="">{{ trans('dashboard.side_4') }}</p>
                        </li>
                        <!--<li class="nav-item">-->
                    <!--    <a href="{{route('my_restaurant_users')}}"-->
                    <!--       class="nav-link {{ strpos(URL::current(), '/restaurant/my_restaurant_users') !== false ? 'active' : '' }}">-->
                        <!--        <i class="nav-icon fa fa-users"></i>-->
                        <!--        <span class="badge badge-info right">-->
                    <!--        {{\DB::select('select count(DISTINCT(user_id)) as count from orders where restaurant_id = ' . $user->id)[0]->count}}-->
                        <!--    </span>-->
                        <!--        <p>-->
                    <!--            @lang('messages.my_restaurant_users')-->
                        <!--        </p>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li class="nav-item {{ strpos(URL::current(), '/restaurant/ads') !== false ? 'active' : '' }}">
                            <a href="{{route('restaurant.ads.index')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/ads') !== false ? 'active' : '' }}">
                                <i class="fas fa-external-link-alt"></i>
                                <p>
                                    @lang('dashboard.ads')
                                </p>
                            </a>
                        </li>

                        <li class="nav-item {{ strpos(URL::current(), '/restaurant/offers') !== false ? 'active' : '' }}">
                            <a href="{{url('/restaurant/offers')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/offers') !== false ? 'active' : '' }}">
                                <i class="fas fa-gift"></i>
                                <p>
                                    @lang('messages.offers')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item {{ strpos(URL::current(), '/restaurant/sliders') !== false ? 'active' : '' }}">
                            <a href="{{url('/restaurant/sliders')}}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/sliders') !== false ? 'active' : '' }}">
                                <i class="fas fa-sliders-h"></i>
                                <p>
                                    @lang('messages.sliders')
                                </p>
                            </a>
                        </li>
                        {{-- loyalty_points --}}
                        @php
                            $loyaltySubscription =   \App\Models\ServiceSubscription::whereRestaurantId($user->type == 'employee' ? $user->restaurant_id : $user->id)->whereHas('service' , function($query){
                                    $query->where('id' , 11);
                                   })
                                    ->whereIn('status' , ['active' , 'tentative'])
                                    ->first()
                        @endphp
                        @if(isset($loyaltySubscription->id))
                            <li class="nav-item has-treeview {{ strpos(URL::current(), 'estaurant/loyalty_point') !== false ? 'menu-open' : '' }}">
                                <a href="#"
                                class="nav-link {{ strpos(URL::current(), '/restaurant/loyalty_point') !== false ? 'active' : '' }}">
                                    <i class="fas fa-comment-dots"></i>
                                    <p>
                                        @lang('dashboard.loyalty_points')
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    {{-- prices --}}
                                    <li class="nav-item">
                                        <a href="{{ url('restaurant/loyalty_point_price') }}"
                                        class="nav-link {{ strpos(URL::current(), 'loyalty_point_price') !== false ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.loyalty_point_prices')
                                            </p>
                                        </a>
                                    </li>
                                    {{-- settings --}}
                                    <li class="nav-item">
                                        <a href="{{ url('restaurant/loyalty_point/settings') }}"
                                        class="nav-link {{ strpos(URL::current(), '/restaurant/loyalty_point/settings') !== false ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                @lang('dashboard.settings')
                                            </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                        {{-- rate --}}
                        
                        <li class="nav-item has-treeview {{ strpos(URL::current(), '/restaurant/feedback') !== false ? 'menu-open' : '' }}">
                            <a href="#"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/feedback') !== false ? 'active' : '' }}">
                                <i class="fas fa-comment-dots"></i>
                                <p>
                                    @lang('dashboard.client_rate')
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- rate --}}

                                <li class="nav-item">
                                    <a href="{{ route('restaurant.feedback.index') }}"
                                       class="nav-link {{ strpos(URL::current(), '/restaurant/feedbackx') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.client_feedback')
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('restaurant/feedback/branch') }}"
                                       class="nav-link {{ strpos(URL::current(), '/restaurant/feedback/branchx') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.branches')
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('restaurant/feedback/branch_setting') }}"
                                       class="nav-link {{ strpos(URL::current(), '/restaurant/feedback/branch_settingx') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.feedback_setting')
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        {{-- contact_us --}}
                        <li class="nav-item has-treeview {{ (strpos(URL::current(), '/restaurant/contact_us') !== false or isUrlActive('link_contact_us')) ? 'menu-open' : '' }}">
                            <a href="#"
                               class="nav-link {{ (strpos(URL::current(), '/restaurant/contact_us') !== false or isUrlActive('link_contact_us')) ? 'active' : '' }}">
                                <i class="fas fa-share-square"></i>
                                <p>
                                    @lang('dashboard.contact_us')
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('restaurant/link_contact_us') }}"
                                       class="nav-link {{ Request::is('restaurant/link_contact_us') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.link_contact_us')
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('restaurant/contact_us') }}"
                                       class="nav-link {{ Request::is('restaurant/contact_us') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.default_link')
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('restaurant/contact_us/settings') }}"
                                       class="nav-link {{ strpos(URL::current(), '/restaurant/contact_us/settings') !== false ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            @lang('dashboard.default_link_settings')
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                    @endif
                    @php
                        $info_permission = \App\Models\RestaurantPermission::whereRestaurantId($user->id)->wherePermissionId(6)->first();
                    @endphp
                    @if($user->type == 'restaurant' or ($info_permission and $user->type == 'employee'))
                        <li class="nav-item sidebar-title">
                            <i class="nav-icon fas fa-info"></i>
                            <p class="">{{ trans('dashboard.side_5') }}</p>
                        </li>
                        {{-- information --}}

                        {{-- social --}}
                        <li class="nav-item">
                            <a href="{{ url('/restaurant/socials') }}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/socials') !== false ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    @lang('messages.socials')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/restaurant/deliveries') }}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/deliveries') !== false ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    @lang('messages.deliveries')
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/restaurant/sensitivities') }}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/sensitivities') !== false ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    @lang('messages.sensitivities')
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/restaurant/information') }}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/information') !== false ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    @lang('messages.information')
                                </p>
                            </a>
                        </li>
                        @if(auth('restaurant')->check() and auth('restaurant')->user()->enable_bank == 'true')
                            <li class="nav-item">
                                <a href="{{ route('restaurant.banks.index') }}"
                                   class="nav-link {{ isUrlActive('banks') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        @lang('messages.banks')
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ url('/restaurant/res_branches') }}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/res_branches') !== false ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    @lang('messages.branches_location')
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/restaurant/my-information') }}"
                               class="nav-link {{ strpos(URL::current(), '/restaurant/my-information') !== false ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    @lang('messages.my_information')
                                </p>
                            </a>
                        </li>

                    @endif
                </ul>
            </nav>
        @else
            @php
                $url = 'https://api.whatsapp.com/send?phone='.\App\Models\Setting::find(1)->active_whatsapp_number.'&text=';
                $content = 'لقد قمت بتسجيل حساب جديد لديكم وأريد تفعيل الفترة التجريبية';
            @endphp
            <a href="{{$url . $content}}" class="btn btn-success" target="_blank">
                <i class="fab fa-whatsapp"></i>
                {{app()->getLocale() == 'ar' ? 'لتفعيل الفترة التجريبية أضغط هنا' : 'To Have The Tentative Period Click Here'}}
            </a>

    @endif
    <!-- Sidebar Menu -->

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
