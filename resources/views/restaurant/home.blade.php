@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.restaurant_control_panel')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{trans('messages.control_panel')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item active">
                            {{trans('messages.control_panel')}}
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    @php
        $user = Auth::guard('restaurant')->user();
    @endphp
    @if(auth('restaurant')->check() and $user->type == 'restaurant')
        @php
            $check_price = \App\Models\CountryPackage::whereCountry_id($user->country_id)
                         ->wherePackageId($user->subscription->package_id)
                         ->first();
                if ($check_price == null) {
                    $package_price = \App\Models\Package::find($user->subscription->package_id)->price;
                } else {
                    $package_price = $check_price->price;
                }
                $tax = \App\Models\Setting::find(1)->tax;
                $subscription_price = $user->subscription->price;
                $tax_value_package = $package_price * $tax / 100;
                $package_price = $package_price + $tax_value_package;
        @endphp
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if($user->subscription != null)
                    <div class="active tab-pane" id="subscription">
                        <!-- The timeline -->
                        <div class="timeline timeline-inverse">

                            <div>
                                <i class="fas fa-user bg-info"></i>

                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        @lang('messages.welcomeRestaurant')
                                        <a href="#">
                                            {{app()->getLocale() == 'ar' ? $user->name_ar : $user->name_en}}
                                        </a>
                                        @lang('messages.at')
                                        @lang('messages.appName')
                                    </h3>
                                </div>
                            </div>
                            <div>
                                <i class="far fa-money-bill-alt bg-gray"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        @lang('messages.package_price')
                                        <a href="#">
                                            {{number_format((float)$subscription_price, 2, '.', '')}}
                                        </a>
                                        {{app()->getLocale() == 'ar' ? $user->country->currency_ar : $user->country->currency_en}}
                                        @lang('messages.including_tax')
                                    </h3>
                                </div>
                            </div>
                            @if($user->subscription->seller_code_id != null)
                                <div>
                                    <i class="far fa-money-bill-alt bg-gray"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header border-0">
                                            @lang('messages.package_price_discount')
                                            @if($user->subscription->seller_code_id != null)
                                                <a href="#">
                                                    {{number_format((float)$subscription_price, 2, '.', '')}}
                                                </a>
                                            @else
                                                <a href="#">
                                                    {{number_format((float)$package_price, 2, '.', '')}}
                                                </a>
                                            @endif
                                            {{app()->getLocale() == 'ar' ? $user->country->currency_ar : $user->country->currency_en}}
                                        </h3>
                                    </div>
                                </div>

                                <div>
                                    <i class="far fa-money-bill-alt bg-gray"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header border-0">

                                            @if($user->subscription->seller_code_id != null)
                                                @if($user->subscription->seller_code->used_type == 'code')
                                                    @lang('messages.seller_code')
                                                    <a href="#">{{$user->subscription->seller_code->seller_name}}</a>
                                                @else
                                                    @lang('messages.seller_code_url')
                                                    <a href="#">{{$user->subscription->seller_code->custom_url}} </a>
                                                @endif

                                            @else
                                                <a href="#">
                                                    @lang('messages.notFound')
                                                </a>
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            @endif
                            <div>
                                <i class="far fa-money-bill-alt bg-gray"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        @lang('messages.next_subscription_price')
                                        @if($user->subscription->seller_code_id != null)
                                            @if($user->subscription->seller_code->permanent == 'true')
                                                <a href="#">
                                                    {{number_format((float)$subscription_price, 2, '.', '')}}
                                                    {{app()->getLocale() == 'ar' ? $user->country->currency_ar : $user->country->currency_en}}
                                                </a>
                                            @else
                                                <a href="#">
                                                    {{number_format((float)$package_price, 2, '.', '')}}
                                                    {{app()->getLocale() == 'ar' ? $user->country->currency_ar : $user->country->currency_en}}
                                                </a>
                                            @endif
                                        @else
                                            <a href="#">
                                                {{number_format((float)$package_price, 2, '.', '')}}
                                                {{app()->getLocale() == 'ar' ? $user->country->currency_ar : $user->country->currency_en}}
                                            </a>
                                        @endif
                                        @lang('messages.including_tax')
                                    </h3>
                                </div>
                            </div>
                            <div>
                                <i class="far fa-clock bg-gray"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        @lang('messages.state')
                                        @if($user->subscription->status == 'active')
                                            <a href="#">
                                                @lang('messages.active')
                                            </a>
                                        @elseif($user->subscription->status == 'tentative')
                                            <a href="#">
                                                @lang('messages.free_tentative_period')
                                            </a>
                                        @elseif($user->subscription->status == 'finished')
                                            <a href="#">
                                                @lang('messages.finished')
                                            </a>
                                        @elseif($user->subscription->status == 'tentative_finished')
                                            <a href="#">
                                                @lang('messages.tentative_finished')
                                            </a>
                                        @endif

                                    </h3>
                                </div>
                            </div>
                            @if($user->subscription->end_at > Carbon\Carbon::now())
                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header border-0">
                                            {{app()->getLocale() == 'ar' ? 'باقي علي أنتهاء الأشتراك الخاص بكم' : 'The rest of your subscription has expired'}}
                                            <a href="#">
                                                <?php
                                                $ticketTime = strtotime($user->subscription->end_at);

                                                // This difference is in seconds.
                                                $difference = $ticketTime - time();
                                                ?>
                                                {{round($difference / 86400)}}
                                            </a>
                                            {{app()->getLocale() == 'ar' ? 'يوم' : 'Day'}}
                                        </h3>
                                        @if($user->subscription->end_at < \Carbon\Carbon::now()->addMonth() && $user->subscription->status == 'active')
                                            <p>
                                                <a class="btn btn-info"
                                                   href="{{route('renewSubscription' , $user->id)}}">
                                                    {{app()->getLocale() == 'ar' ? 'تجديد الأشتراك' : 'Renew Subscription'}}
                                                </a>
                                            </p>
                                        @endif
                                        @if($user->subscription->status == 'tentative')
                                            <a class="btn btn-success"
                                               href="{{route('renewSubscription' , $user->id)}}">
                                                {{app()->getLocale() == 'ar' ? 'تفعيل الأشتراك' : 'Active Subscription'}}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div>
                                <i class="far fa-clock bg-gray"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        @lang('messages.subscribe_end_at')
                                        <a href="#">
                                            {{$user->subscription->end_at->format('Y-m-d')}}
                                        </a>
                                        @if($user->subscription->status == 'finished' || $user->subscription->status == 'tentative_finished')
                                            <a class="btn btn-danger" href="#">
                                                @lang('messages.finished')
                                            </a>
                                            <hr>
                                            <a class="btn btn-success"
                                               href="{{route('renewSubscription' , $user->id)}}"> @lang('messages.renewSubscription') </a>
                                        @endif
                                    </h3>
                                </div>
                            </div>

                            <div>
                                <i class="far fa-money-bill-alt bg-gray"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        @lang('messages.menu_total_views')
                                        <a href="#">
                                            {{$user->views}}
                                        </a>
                                    </h3>
                                </div>
                            </div>
                            <div>
                                <i class="far fa-money-bill-alt bg-gray"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header border-0">
                                        {{app()->getLocale() == 'ar' ? 'الزيارات اليومية' : 'Daily Views'}}
                                        <a href="#">
                                            <?php $daily_views = \App\Models\RestaurantView::whereRestaurantId($user->id)->orderBy('id', 'desc')->first(); ?>
                                            @if($daily_views != null)
                                                {{$daily_views->views}}
                                            @else
                                                0
                                            @endif

                                        </a>
                                    </h3>
                                </div>
                            </div>
                            <div>
                                <i class="far fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                    @if($user->admin_activation == 'true')
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-info">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{$user->branches->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.branches')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="ion ion-bag"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{url('/restaurant/branches')}}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i>--}}
                        {{--                                </a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <!-- ./col -->--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-success">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\MenuCategory::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>@lang('messages.menu_categories')</p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="ion ion-stats-bars"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{url('/restaurant/menu_categories')}}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i>--}}
                        {{--                                </a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <!-- ./col -->--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-warning">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\Modifier::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.modifiers')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fa-plus"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{url('/restaurant/modifiers')}}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <!-- ./col -->--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-danger">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\Option::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>@lang('messages.options')</p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="ion ion-pie-graph"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{url('/restaurant/options')}}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i>--}}
                        {{--                                </a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-primary">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\RestaurantPoster::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.posters')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fa-print"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{route('posters.index')}}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-gray-dark">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\Product::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.products')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fa-print"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{route('products.index')}}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-gray">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\RestaurantSocial::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.socials')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fa-eye"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{ url('/restaurant/socials') }}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-green">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\RestaurantDelivery::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.deliveries')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fa-truck"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{ url('/restaurant/deliveries') }}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-red">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\RestaurantSensitivity::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.sensitivities')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="far fa-circle nav-icon"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{ url('/restaurant/sensitivities') }}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-yellow">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\RestaurantOffer::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.offers')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fa-gift"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{ url('/restaurant/offers') }}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-lg-3 col-6">--}}
                        {{--                            <!-- small box -->--}}
                        {{--                            <div class="small-box bg-info">--}}
                        {{--                                <div class="inner">--}}
                        {{--                                    <h3>--}}
                        {{--                                        {{\App\Models\RestaurantSlider::whereRestaurantId($user->id)->count()}}--}}
                        {{--                                    </h3>--}}

                        {{--                                    <p>--}}
                        {{--                                        @lang('messages.sliders')--}}
                        {{--                                    </p>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="icon">--}}
                        {{--                                    <i class="fa fas fa-sliders-h"></i>--}}
                        {{--                                </div>--}}
                        {{--                                <a href="{{ url('/restaurant/sliders') }}" class="small-box-footer">--}}
                        {{--                                    @lang('messages.details')--}}
                        {{--                                    <i class="fas fa-arrow-circle-right"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <!-- ./col -->--}}
                        {{--                    </div>--}}
                    @else
                        @php
                            $url = 'https://api.whatsapp.com/send?phone='.\App\Models\Setting::find(1)->active_whatsapp_number.'&text=';
                            $content = 'لقد قمت بتسجيل حساب جديد لديكم وأريد تفعيل الفترة التجريبية';
                        @endphp
                        <a href="{{$url . $content}}" class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            {{app()->getLocale() == 'ar' ? 'لتفعيل الفترة التجريبية أضغط هنا' : 'To Have The Tentative Period Click Here'}}
                        </a>
                        <br>
                        <br>
                        <br>
                    @endif
                @else
                    <h3 class="text-center">
                        {{app()->getLocale() == 'ar' ? 'عفوا لم تكتمل عمليه التسجيل لديك برجاء التواصل مع الأدارة' : 'Sorry Your Registration Not Complete Contact Administration'}}
                    </h3>
            @endif

            <!-- Small boxes (Stat box) -->

                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    @else
        <h1>
            @lang('messages.welcome')
            {{$user->name_ar}}
        </h1>
    @endif
@endsection
