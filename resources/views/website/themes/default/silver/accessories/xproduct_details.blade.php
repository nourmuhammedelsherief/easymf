@push('styles')


@endpush
@include('website.'.session('theme_path').'silver.layout.header')

@php
    $isFullPageProduct = true;
@endphp

<div id="page"style="">
    <div id="footer-bar" class="footer-bar-5">
        <div class="clear"></div>
        @if($branch->cart == 'true')
            
            
            
                @if($table != null)
                    @php
                        $check_table_order = \App\Models\TableOrder::whereStatus('in_reservation')
                           ->where('ip' , '!=' , Request::ip())
                           ->where('table_id' , $table->id)
                           ->first();
                    @endphp
                    @if($check_table_order == null)
                        @php
                            $cartLink = route('tableGetCart', [$branch->id , $table->id]);
                            $cartCount =   \App\Models\TableOrderItem::with('product' , 'table_order')
                                 ->whereHas('product', function ($q) use ($branch) {
                                    $q->where('branch_id', $branch->id);
                                 })
                                 ->whereHas('table_order', function ($q) use ($table) {
                                    $q->where('status' , 'in_reservation');
                                    $q->where('ip' , Request::ip());
                                    $q->where('table_id' , $table->id);
                                 })->count();
                        @endphp
                        {{-- <a href="{{route('tableGetCart', [$branch->id , $table->id])}}"
                           style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons }} !important">
                    <span>
                        {{
                            \App\Models\TableOrderItem::with('product' , 'table_order')
                                 ->whereHas('product', function ($q) use ($branch) {
                                    $q->where('branch_id', $branch->id);
                                 })
                                 ->whereHas('table_order', function ($q) use ($table) {
                                    $q->where('status' , 'in_reservation');
                                    $q->where('ip' , Request::ip());
                                    $q->where('table_id' , $table->id);
                                 })->count()
                        }}
                    </span>
                            <i class="fa fa-shopping-cart"
                               style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></i>
                        </a> --}}
                    @endif
                @endif
                @php
                    $checkOrderService = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                                            ->whereIn('service_id' , [5 , 6 , 7 , 9 , 10])
                                            ->where('status' , 'active')
                                            ->first();
                @endphp
                @if(auth('web')->check() and ($branch->foodics_status == 'true' && $table == null) || ($checkOrderService == null && $table == null))
                    @php
                        $cartLink = route('silverGetCart', $branch->id);
                        $cartCount =    \App\Models\SilverOrder::with('product')
                                ->whereHas('product', function ($q) use ($branch) {
                                $q->where('branch_id', $branch->id);
                                    })
                                    ->whereUserId(\Illuminate\Support\Facades\Auth::guard('web')->user()->id)
                                    ->where('status' , 'in_cart')
                                ->count();
                    @endphp
                    {{-- <a href="{{route('silverGetCart', $branch->id)}}"
                       style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons }} !important">
                        <span>
                            {{
                                \App\Models\SilverOrder::with('product')
                                ->whereHas('product', function ($q) use ($branch) {
                                $q->where('branch_id', $branch->id);
                                    })
                                    ->whereUserId(\Illuminate\Support\Facades\Auth::guard('web')->user()->id)
                                    ->where('status' , 'in_cart')
                                ->count()
                            }}
                        </span>
                        <i class="fa fa-shopping-cart"
                           style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></i>
                    </a> --}}
                @elseif(auth('web')->check() and $checkOrderService and $branch->foodics_status == 'false' and $table == null)
                @php
                    $cartLink = route('goldGetCart', $branch->id);
                    $cartCount =   \App\Models\OrderItem::with('product' , 'order')
                                 ->whereHas('product', function ($q) use ($branch) {
                                    $q->where('branch_id', $branch->id);
                                 })
                                 ->whereHas('order', function ($q){
                                    $q->where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id);
                                    $q->where('status' , 'in_reservation');
                                 })->count();
                @endphp
                    {{-- <a href="{{route('goldGetCart', $branch->id)}}"
                       style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons }} !important">
                        <span>
                            {{
                                \App\Models\OrderItem::with('product' , 'order')
                                    ->whereHas('product', function ($q) use ($branch) {
                                        $q->where('branch_id', $branch->id);
                                    })
                                    ->whereHas('order', function ($q){
                                        $q->where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id);
                                        $q->where('status' , 'in_reservation');
                                    })->count()
                            }}
                        </span>
                        <i class="fa fa-shopping-cart"
                           style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></i>
                    </a> --}}
                @endif
            
        @endif
    </div>
    <div class="page-content pb-0">
        {{-- start menu product --}}
        @include('website.'.session('theme_path').'silver.accessories.menu_product')
        {{-- end menu product --}}
        
        @if(isset($cartLink) and isset($cartCount) )
        <div id="cart-count" class="cart-count {{$cartCount == 0 ? 'hide' : '' }}">
            <a href="{{$cartLink}}" class="cart-btn">
                <i class="fa fa-shopping-cart"></i>
                [<span class="count">{{$cartCount}}</span>]
                <span>{{ trans('messages.cart_count') }}</span>
            </a>
        </div>
    @endif
    </div>
    <style>
        body{
         @if(isset($cartCount) and $cartCount > 0)
            margin-bottom: 50px !important;
         @endif 
        }
     </style>
    </div>
    
    @include('website.'.session('theme_path').'silver.layout.footer')
</div>



@push('scripts')
<script>

    $(function(){
        
			$('body').on('click' , '.copy-url' , function(){
				var tag = $(this);
				console.log($("#product-" + tag.data('id') + '-url'));
				$("input#product-" + tag.data('id') + '-url').select();
    			// document.execCommand('copy');
				copyToClipboard("input#product-" + tag.data('id') + '-url');
				var t = tag.parent().parent().find('.success-copy-url');
				t.fadeIn(300);
				setTimeout(() => {
					t.fadeOut(300);
				}, 1000);
			});
		
        	$('body').on('click' , '.share-product' , function(){
				var tag = $(this);
				console.log('share');
				var url = $("input#product-" + tag.data('id') + '-url').val();
				if (navigator.share) {
					navigator.share({
						title: tag.data('title'),
						url: url, 
					})
					.then(() => console.log('Successful share'))
					.catch((error) => console.log('Error sharing', error));
				} else {
					console.log('Share not supported on this browser, do it the old way.');
				}
			});
    });
</script>
@endpush

@include('website.'.session('theme_path').'silver.layout.scripts')
