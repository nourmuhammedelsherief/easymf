@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.control_panel')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('messages.control_panel')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">@lang('messages.control_panel')</a></li>
                        {{--                        <li class="breadcrumb-item active">Dashboard v1</li>--}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                {{\App\Models\Restaurant::whereHas('subscription',function ($q){
                                    $q->where('status' , 'active');
                                    $q->where('package_id' , 1);
                                    $q->where('type' , 'restaurant');
                                 })
                                 ->where('archive' , 'false')
                                 ->where('status' , 'active')
                                 ->count()
                                 }}
                            </h3>

                            <p>@lang('messages.restaurants')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('/admin/restaurants/active')}}"
                           class="small-box-footer">@lang('messages.details')
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>
                                {{$restaurants = \App\Models\Restaurant::where('admin_activation' , 'false')->count()}}
                            </h3>

                            <p>
                                @lang('messages.restaurants') @lang('messages.restaurantsInActive')
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('/admin/restaurants/InActive')}}"
                           class="small-box-footer">@lang('messages.details')
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h3>
                                {{$restaurants = \App\Models\Restaurant::where('status' , 'inComplete')
                                ->where('archive' , 'false')->count()}}
                            </h3>

                            <p>
                                @lang('messages.rest_inComplete')
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('/admin/restaurants/inComplete')}}"
                           class="small-box-footer">@lang('messages.details')
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>
                                {{$branches = \App\Models\Branch::where('status' , 'not_active')
                                ->where('main' , 'false')->count()}}
                            </h3>

                            <p>
                                @lang('messages.branch_inComplete')
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('/admin/branches/in_complete')}}"
                           class="small-box-footer">@lang('messages.details')
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{\App\Models\Branch::with('subscription')->whereHas('subscription',function ($q){$q->where('package_id' , 1);})->where('status' , 'active')->where('main' , 'false')->count()}}</h3>

                            <p>@lang('messages.branches')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('/admin/branches/active')}}" class="small-box-footer">@lang('messages.details')
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                @if(auth('admin')->user()->role == 'admin')
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{\App\Models\User::count()}}</h3>

                                <p>@lang('messages.clients')</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{url('/admin/clients')}}" class="small-box-footer">@lang('messages.details')
                                <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>
                                    {{\App\Models\History::count()}}
                                </h3>

                                <p>@lang('messages.histories')</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fa fa-history"></i>
                            </div>
                            <a href="{{url('/admin/histories')}}" class="small-box-footer">@lang('messages.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>
                                    {{-- {{\App\Models\Subscription::where('transfer_photo' , '!=' , null)->where('type' , 'restaurant')->where('payment_type' , 'bank')->whereIn('status' , ['finished' , 'tentative_finished'])->count()}} --}}
                                    {{\App\Models\Subscription::where('transfer_photo' , '!=' , null)
                                    ->where('type' , 'restaurant')
                                    ->where('payment_type' , 'bank')
                                    ->whereIn('status' , ['finished' , 'tentative_finished'])
                                    ->orWhere('payment' , 'true')
                                    ->where('type' , 'restaurant')
                                    ->where('payment_type' , 'bank')
                                    ->where('transfer_photo' , '!=' , null)
                                    ->count()}}
                                </h3>

                                <h6>
                                    @lang('messages.bank_transfers')  @lang('messages.restaurantTransfer')
                                </h6>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money-bill"></i>
                            </div>
                            <a href="{{ route('subscription.confirm') }}"
                               class="small-box-footer">@lang('messages.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>
                                    {{\App\Models\Subscription::where('transfer_photo' , '!=' , null)->where('type' ,  'branch')->where('payment_type' , 'bank')->where('status' , '!=' , 'active')->count()}}
                                </h3>

                                <h6>
                                    @lang('messages.bank_transfers')  @lang('messages.branchTransfer')
                                </h6>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money-bill"></i>
                            </div>
                            <a href="{{ route('subscription.confirm_branch') }}"
                               class="small-box-footer">@lang('messages.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>
                                    {{\App\Models\ServiceSubscription::whereNull('paid_at')->where('type' , 'bank')->whereNotNull('photo')
                                    ->count()}}
                                </h3>

                                <h6>
                                    @lang('messages.bank_transfers')  {{ trans('dashboard.services_subscription') }}
                                </h6>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money-bill"></i>
                            </div>
                            <a href="{{ route('admin.service.subscription_confirm') }}"
                               class="small-box-footer">@lang('messages.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
            @endif

            <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
