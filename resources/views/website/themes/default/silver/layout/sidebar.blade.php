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
<div id="web-sidebar">
    <div class="sidebar-content close mobile-width">
        <div class="close-sidebar"><i class="fas fa-times"></i></div>
        <div class="sidebar">
            <div class="sidebar-header">
                <a class="logo" href="{{url()->current()}}">
                    <img src="{{empty($restaurant->logo) ? '' : asset($restaurant->image_path)}}" alt="">
                </a>
                <div class="title">
                    @if(isset($table->id)) طلب من طاولة
                    @elseif(auth('web')->check())
                        {{auth('web')->user()->phone_number}}
                    @endif
                </div>
            </div>
            <div class="sidebar-body">
                @if($table == null  and $checkLoyaltyPoint == true)
                
                    <a href="{{route('loyalty_points' , [$restaurant->id , $branch->id])}}">{{ trans('dashboard.loyalty_points') }} </a>
                
                @endif
                @php
                $order = \App\Models\TableOrder::orderBy('id' , 'desc')
                    ->where('status' , '!=' , 'in_reservation')
                    ->where('ip', \Illuminate\Support\Facades\Session::getId())
                    ->first();
            @endphp
      
                @if($table != null and isset($order->id))
                
                    <a href="{{$order != null ? route('TableReceivedOrder' , $order->id) : '#'}}">تتبع اخر طلب </a>
                
                @endif
                @if($table != null)
                <a href="{{route('TableReceivedOrder')}}">ارشيف طلباتي </a>
                @endif
              
              
                @if(auth('web')->check() and $table == null)
                    
                        <a href="{{url('user/logout')}}">{{ trans('messages.logout') }}</a>
                    
                @endif
            </div>
        </div>
            <a href="https://easymenu.site/" class="tail" target="_blank"><p class="footer-copyright pb-3 mb-1 pt-0 mt-0 font-13 font-600"
                style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->main_heads }} !important"
                >@lang('messages.made_love')
                <i class="fa fa-heart font-14 color-red1-dark"></i>
                @lang('messages.at_easyMenu')
                </p></a>

    </div>
</div>

<style>
    #web-sidebar{
        position: relative;
        color : {{!isset($restaurant->color->id) ? '#000' : $restaurant->color->main_heads}};
    }
    #web-sidebar .sidebar{
        padding: 20px;
    }

    #web-sidebar .sidebar-header .logo{
        display: block;
        width: 60px;
        height: 60px;
        border-radius: 100%;
        overflow: hidden;
        margin-top: 20px;
        margin-bottom: 30px;
    }
    #web-sidebar .sidebar-header .logo img{
        
        width: 100%;
        height: 100%;
        border-radius: 100%;
    }

    #web-sidebar .sidebar-header  .title {
        position: absolute;
        top: 60px;
        right: 92px;
        font-size: 16px;
        font-weight: bold;
    }
   
    #web-sidebar .sidebar-content .close-sidebar{
        width: 40px;
        height: 40px;
        background-color: #CCC;
        border-radius: 100%;
        position: absolute;
        top: 10px;
        left: 20px;
        display: flex;
        justify-content: center;
    }
    #web-sidebar .sidebar-content .close-sidebar i{
        font-size: 18px;
        margin-top: 12px;
    }
    #web-sidebar .sidebar-content{
        position: fixed;
        top: 0px;
        width: 100%;
        background-color: #0000001c;
        height: 100%;
        z-index : 99;
    }
    #web-sidebar .tail{
        position: absolute;
    bottom: 0;
    right: 67px;
    display: block;
    }

    #web-sidebar .sidebar-content.open{
        transform: translateX(0%);
        opacity: 1;
        transition: 0.3s ease;
    }
    #web-sidebar .sidebar-content.close{
        transform: translateX(100%);
        opacity: 0;
        transition: 0.3s ease;
    }
    #web-sidebar .sidebar-body{
        padding-top: 0px;
        border-top: 2px solid {{!isset($restaurant->color->id) ? '#000' : $restaurant->color->main_heads}};
    }
    #web-sidebar .sidebar-body a{
        display: block;
        font-size: 16px;
        font-weight: bold;
        padding: 15px 0 ; 
        color :  {{!isset($restaurant->color->id) ? '#000' : $restaurant->color->main_heads}};
        border-bottom : 1px solid  {{!isset($restaurant->color->id) ? '#000' : $restaurant->color->main_heads}};
    }
    #web-sidebar .sidebar-body a:focus{
        color :  {{!isset($restaurant->color->id) ? '#000' : $restaurant->color->main_heads}};
    }
    #web-sidebar .sidebar-content .sidebar{
        /* position: fixed;
        top: 0px; */
        width: 270px;
        z-index : 101;
        background-color: {{ !isset($restaurant->color->id) ? '#FFF' : $restaurant->color->background}};
        height: 100%;
    }
    .sidebar-body{
        color:#FFF;
    }

</style>