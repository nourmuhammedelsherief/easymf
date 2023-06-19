<div id="menu-profile"
     class="menu menu-box-med menu-box-detached rounded-l"
     data-menu-height="120"
     data-menu-effect="menu-over">

    @php
        $dd = 6;
        $loyaltySubscription =  \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)->whereHas('service' , function($query){
            $query->where('id' , 11);
           })
            ->whereIn('status' , ['active' , 'tentative'])
            ->where('branch_id' , $branch->id)
            ->first();
        if(  $restaurant->enable_loyalty_point == 'true' and isset($loyaltySubscription->id) ){
            $checkLoyaltyPoint = true;
            $dd = 4;
        }
        else $checkLoyaltyPoint = false;
    @endphp
    <div class="row text-center pt-4">
        {{-- <div class="col-{{$dd}}">
            <div class="item bg-theme">
                <a href="#" class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark"><i
                    class="fa fa-shopping-cart font-20"></i></a>
                <h5 class="font-12 mt-2"> طلباتي </h5>

            </div>
        </div> --}}
        @if($checkLoyaltyPoint)
        <div class="col-{{$dd}}">
            <div class="item bg-theme">
                <a href="{{route('loyalty_points' , [$restaurant->id , $branch->id])}}" class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark"><i
                    class="fa fa-shopping-cart font-20"></i></a>
                <h5 class="font-12 mt-2"> {{ trans('dashboard.loyalty_points') }} </h5>

            </div>
        </div>
        @endif

        @php
            $order = \App\Models\TableOrder::orderBy('id' , 'desc')
                ->where('status' , '!=' , 'in_reservation')
                ->where('ip', \Illuminate\Support\Facades\Session::getId())
                ->first();
        @endphp
        @if($order)
            <div class="col-{{$dd}}">
                <div class="item bg-theme">
                    <a href="{{$table != null ? route('TableReceivedOrder' , $order->id) : '#'}}" data-menu="menu-orders"
                       class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark"><i
                            class="fa fa-shopping-cart font-20"></i></a>
                    <h5 class="font-12 mt-2"> تتبع اخر طلب</h5>

                </div>
            </div>
        @endif


        <div class="col-{{$dd}}">
            <div class="item bg-theme">
                <a href="{{ route('user_logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                   class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark">
                    <i class="fa fa-sign-out-alt font-20"></i>
                </a>
                <h5 class="font-12 mt-2">@lang('messages.logout')</h5>
                <form id="logout-form" action="{{ route('user_logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </div>


    </div>
</div>
<style>
    .mt-2 {
        color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads }}    !important;
    }
</style>
