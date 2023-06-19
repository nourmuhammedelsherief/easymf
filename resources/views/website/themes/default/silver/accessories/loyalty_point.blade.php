@php
    if(isset($restaurant->id)){
        $pageTitle = app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en;
        $pageIcon = asset('uploads/restaurants/logo/' . $restaurant->logo);
    }

@endphp
@include('website.'.session('theme_path').'silver.layout.header')
<!--<div id="preloader" style="text-align:center;">
    <div class="lds-ripple"><div></div><div></div></div>
</div>-->
<style>
.convert-price a{
    font-size: 14px;
    padding: 5px 10px;
    margin-top: 30px;
}
.price-list .card-header{
    background-color: transparent !important;
    padding: 10px ;
    
}
table{
    width: 100%;
}
table thead tr{
    /* border-bottom: 1px solid #CCC; */
    background-color: rgb(85, 108, 255);
    color : #FFF;
    
}
table th {
    padding: 10px 0;
}
table th , 
table td{
    text-align: center;
}
table tbody tr:nth-child(even){
    background-color: #f1f1f1;
}
.to-rest{
    margin-top: 60px;
}
.to-rest a{
    font-size: 14px;
}
.balance {
    display: flex;
    justify-content: space-between;
    width:100%;
}
/* .balance div:nth-child(2){
    margin:auto;
} */
.balance > div{
    display: inline;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function () {
        $('.scrolling-pagination').jscroll({
            autoTrigger: true,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function () {
                $('ul.pagination').remove();
            }
        });
    });
</script>
<div id="page" >
    <!-- header and footer bar go here-->
    @include('website.'.session('theme_path').'silver.layout.head')
    @include('flash::message')

   

    <div class="page-content pb-0">
        
            
        

        <div class="content">
            <h1 class="text-center mt-5 mb-5">{{ trans('dashboard.loyalty_points') }}</h1>
            
            <div class="balance">
                <div class="">
                    {{ trans('messages.points_count') }} : <span>{{$points}}</span>
                </div>
                <div class="">
                    {{ trans('messages.orders_m') }} : <span>{{$ordersCount}}</span>
                </div>
                <div class="">
                    {{ trans('messages.balance') }} : <span>{{$totalBalance}} ريال</span>
                </div>
            </div>
            <div class="convert-price text-center" style="clear:both;">
                @if(empty($hint))
                    <a href="javascript:;" class="btn btn-secondary">{{ trans('messages.empty_balance') }}</a>
                @else 
                    <a href="{{route('convertLoyaltyPoint' , $restaurant->id)}}" class="btn btn-primary" disabled>{{ trans('messages.convert_to_balance') }}</a>

                <p class="text-center text-success">{{$hint}}</p>
                @endif
            </div>

            <div class="price-list card">
                <div class="card-header text-center mt-4">
                    {{trans('messages.price_list')}}
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ trans('messages.points') }}</th>
                                <th>{{ trans('messages.price_rayal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($priceList as $item)
                                <tr>
                                    <td>{{$item->points}}</td>
                                    <td>{{$item->price}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-center to-rest">
                        <a href="{{$branch->main == "true" ? url('restaurants/' . $restaurant->name_barcode . ($branch->main == 'true' ? '' : ( '/' . $branch->name_barcode) )) : route('sliverHomeBranch', [$restaurant->name_barcode , $branch->name_barcode])}}" class="btn btn-info">{{ trans('messages.to_to_restaurant') }}</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <!-- footer and footer card-->
    @include('website.'.session('theme_path').'silver.layout.footer')
</div>
<!-- end of page content-->

<!-----------menu-profile---------------------------->
@include('website.'.session('theme_path').'silver.accessories.profile')
@include('website.'.session('theme_path').'silver.layout.sidebar')


@include('website.'.session('theme_path').'silver.accessories.res_branches')
@include('website.'.session('theme_path').'silver.accessories.related')
@include('website.'.session('theme_path').'silver.layout.scripts')
<script>
    $(function(){
        console.log('log')
        @if(session('success')):
            toastr.success("{{session('success')}}");
        @elseif(session('error'))
            toastr.error("{{session('error')}}");
        @endif
    });
</script>


