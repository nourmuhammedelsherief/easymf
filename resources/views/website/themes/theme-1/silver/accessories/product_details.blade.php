@push('styles')


@endpush
@include('website.'.session('theme_path').'silver.layout.header')



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
       <div id="menu-prodact-{{$meal->id}}">
            <div class="product-details">
                {{-- <div class="content header pt-2 pb-4">
            
            
            
            
                </div> --}}
                <div class="card  shape-rounded rounded-lp"
                    style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->product_background}} !important">
            
                    <div class="prodsld" style="position: relative">
                        <a href="{{session('product_back_to')}}" class="chip chip-small bg-black2 go-back  clowind"
                        style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important;position:absolute;top:0;left:10px;">
                            <i class="fas fa-arrow-left bg-yellow1-dark "
                            style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important"></i>
            
                        </a>
                        <div>
                            <div class="card shadow-xl">
                                @if($meal->foodics_image != null)
                                    <img
                                        data-src="{{$meal->foodics_image}}"
                                        style="width:100%;"/>
                                @else
                                    <img
                                        data-src="{{empty($meal->photo) ? asset($meal->restaurant->image_path) : asset($meal->image_path)}}"
                                        style="width:100%;"/>
                            @endif
            
                            <!--  <div class="card-overlay "></div>
                                <div class="card-overlay"></div>-->
                            </div>
                            <h4 class="mb-1 font-14 product-title"
                                style="padding:0 20px;color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                    <span class="pl-1">
                                        @if($meal->poster != null)
                                            <i>
                                                    <img
                                                        data-src="{{asset('/uploads/posters/' . $meal->poster->poster)}}"
                                                        height="40"
                                                        width="40">
                                                </i>
                                        @endif
                                    </span>
                                {{app()->getLocale() == 'ar' ? $meal->name_ar : $meal->name_en}}
            
                                @if($meal->calories != null)
                                    <span class="pl-1">
                                        <span
                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                            {{$meal->calories}}
                                        </span>
                                        <i class="fa fa-fire pr-1 color-red2-dark"></i>
                                    </span>
                                @endif
                            </h4>
            
                        </div>
            
                    </div>
            
            
                </div>
            
            
                <div>
            
                    <div class="rounded-lp mt-n2 pl-3  pr-3"
                        style="position: relative; background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->product_background}} !important;">
            
            
                        <div class="content pt-4">
            
                        <!--<h4 class="mb-1 font-14"
                                style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                {{app()->getLocale() == 'ar' ? $meal->name_ar : $meal->name_en}}
                            </h4>-->
                            <p class="mb-3 font-13 color-dark1-dark "
                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important;">
                                {!! app()->getLocale() == 'ar' ? strip_tags($meal->description_ar) : strip_tags($meal->description_en) !!}
                            </p>
            
            
                        </div>
                        {{--        @dd(\Request::route()->getName())--}}
                        @if($table != null)
                            <form method="post" action="{{route('silverAddToTableCart')}}" id="silverCartForm-{{$meal->id}}"
                                class="silver-cart-form">
                                @else
                                    <form method="post" action="{{route('silverAddToCart')}}" id="silverCartForm-{{$meal->id}}"
                                        class="silver-cart-form">
                                        @endif
                                        @csrf
                                        @if(isset($branch->id))
                                            <input type="hidden" name="branch_id" value="{{$branch->id}}">
                                        @endif
                                        <input type="hidden" name="mealId" value="{{$meal->id}}">
                                        <input type="hidden" id="default-price{{$meal->id}}" value="{{$meal->price}}">
            
                                        @if($table != null)
                                            <input type="hidden" name="table_id" value="{{$table->id}}">
                                        @endif
                                        @if($meal->sizes->count() > 0)
                                            <div class="content my-product-content">
                                                <h4 class="mb-1 font-14"
                                                    style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important"> @lang('messages.sizes')   <span style="color: red; font-size: 10px">
                                            {{app()->getLocale() == 'ar' ? 'مطلوب':'required'}}
                                        </span></h4>
                                                @foreach($meal->sizes as $size)
                                                    <div class="fac fac-radio fac-orange mb-1">
                                                        <label for="size{{$size->id}}-fac-radio" class="color-dark1-dark font-13">
                                                            <input id="size{{$size->id}}-fac-radio" type="checkbox"
                                                                data="{{$size->product->id}}"
                                                                class="size_price" data-size="{{$size->id}}"
                                                                data-product_id="{{$meal->id}}"
                                                                data-price="{{$size->price}}"
                                                                name="size_id[]"
                                                                data-id="{{$meal->price}}"
                                                                value="{{$size->price}}">
                                                            <span class="content-size-{{$size->id}}"></span>
                                                            <span class="checkmark"
                                                                style="border-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important"></span>
                                                            <span class="minw100"
                                                                style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important">
                                                {{app()->getLocale() == 'ar' ? $size->name_ar : $size->name_en}}
                                                @if(!empty($size->calories))
                                                        <i class="fa fa-fire pr-3 color-black-dark"></i>
                                                        {{$size->calories}}
                                                @endif
                                            </span>
                                                            
                                                        </label>
                                                        <h1 class="font-11 float-left mr-2 mt-2"
                                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important">{{$size->price}} {{app()->getLocale() == 'ar' ? $meal->branch->country->currency_ar : $meal->branch->country->currency_en}}</h1>
                                                    </div>
            
                                                @endforeach
                                            </div>
                                        @endif
            
                                        @php
                                            $check_required_options = \App\Models\ProductOption::whereProductId($meal->id)
                                            ->where('min' , '>=' , 1)
                                            ->first();
                                        @endphp
                                        @if($main_additions->count() > 0)
                                            @if($meal->options->count() > 0)
                                                @foreach($main_additions as $main_addition)
                                                    <?php
                                                    $options = \App\Models\ProductOption::with('option')
                                                        ->whereHas('option', function ($q) {
                                                            $q->where('is_active', 'true');
                                                        })
                                                        ->whereProduct_id($meal->id)
                                                        ->where('modifier_id', $main_addition->modifier->id)
                                                        ->get();
                                                    ?>
                                                    @if($options->count() > 0)
                                                        <div class="content">
                                                            <h4 class="mb-1 font-14"
                                                                style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                                                {{app()->getLocale() == 'ar' ? $main_addition->modifier->name_ar : $main_addition->modifier->name_en}}
                                                                @if($main_addition->modifier->choose == 'one')
                                                                    ({{app()->getLocale() == 'ar' ? 'اختيار واحد فقط':'choose one only'}}
                                                                    )
                                                                @endif
                                                                @if($check_required_options)
                                                                    <span style="color: red; font-size: 10px">
                                                                        {{app()->getLocale() == 'ar' ? 'مطلوب':'required'}}
                                                                    </span>
                                                                @endif
                                                            </h4>
                                                            @foreach($options as $option)
                                                                <div class="fac mb-1 addition-item">
                                                                    <label for="box{{$option->option->id}}{{$meal->id}}-fac-radio"
                                                                        class="color-dark1-dark font-13 ">
                                                                        <input
                                                                            id="box{{$option->option->id}}{{$meal->id}}-fac-radio"
                                                                            data="{{$option->product->id}}"
                                                                            class="activity_price" data-id="{{$option->option->id}}"
                                                                            data-required="{{$check_required_options ? 'true' : 'false'}}"
                                                                            data-min="{{$option->min}}" ,
                                                                            data-max="{{$option->max}}" , 
                                                                            data-choose_one="{{$main_addition->modifier->choose == 'one' ? 'true' : 'false'}}"
                                                                            data-main_id="{{$main_addition->modifier->id}}"
                                                                            type="checkbox" name="options[]"
                                                                            style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important"
                                                                            data-price="{{$option->option->price}}"
                                                                            value="{{$option->option->id}}">
                                                                        <input type="hidden" name="options_ids[]"
                                                                            value="{{$option->option->id}}">
                                                                        <span class="checkmark"
                                                                            style="border-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important">
                                                                        </span>
                                                                        <span
                                                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important">
                                                                {{app()->getLocale() == 'ar' ? $option->option->name_ar : $option->option->name_en}}
                                                                @if(!empty($option->option->calories))
                                                                    <i class="fa fa-fire pr-3 color-black-dark"></i>
                                                                    {{$option->option->calories}}
                                                            @endif
                                                                @if($check_required_options)
                                                                <span class="required text-danger" style="font-size: 1.6rem;">*</span>
                                                                @endif
                                                            </span>
            
                                                                    </label>
                                                                    <h1 class="font-11 float-left mr-2">
                                                        <span data-id="{{$meal->id}}" id="{{$option->option->id}}{{$meal->id}}"
                                                                style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important">
                                                                {{$option->option->price * ($option->min > 1 ? $option->min : 1)}}
                                                        </span>
                                                                        <span
                                                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important">
                                                            {{app()->getLocale() == 'ar' ? $meal->branch->country->currency_ar : $meal->branch->country->currency_en}}
                                                        </span>
                                                                    </h1>
                                                                    <div class="float-left">
                                                                        <div class="input-style input-style-2 mt-0 d-flex">
                                                                            <?php $name = 'qty3'; ?>
                                                                            @if(isset($meal->branch->id) and $meal->branch->cart == 'true')
                                                                            <button type="button"
                                                                                    value="{{$option->option->price}}"
                                                                                    data-id="{{$meal->id}}"
                                                                                    data="{{$option->option->id}}"
                                                                                    class="incress option_increase"
                                                                                    data-required="{{$check_required_options ? 'true' : 'false'}}"
                                                                                    data-min="{{$option->min}}" 
                                                                                    data-max="{{$option->max}}" 
                                                                                    style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important">
                                                                                +
                                                                            </button>
                                                                            
                                                                            <input
                                                                                style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->options_description}} !important"
                                                                                name="qty{{$option->option->id}}{{$meal->id}}"
                                                                                type="number"
                                                                                data-required="{{$check_required_options ? 'true' : 'false'}}"
                                                                                data-min="{{$option->min}}" 
                                                                                data-max="{{$option->max}}" 
                                                                                readonly
                                                                                value="{{$option->min > 1 ? $option->min : 1}}" class="border-1 cbox addition">
                                                                                
                                                                            <button type="button"
                                                                                    value="{{$option->option->price}}"
                                                                                    data-id="{{$meal->id}}"
                                                                                    data-required="{{$check_required_options ? 'true' : 'false'}}"
                                                                                    data-min="{{$option->min}}" 
                                                                                    data-max="{{$option->max}}" 
                                                                                    class="incress minn option_decrease {{$branch->id}}"
                                                                                    data="{{$option->option->id}}">-
                                                                            </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
            
                                        @endif
            
                                        <div class="content">
                                            @if($meal->branch->cart == 'true')
                                                <h4 class="mb-1 font-14"
                                                    style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important"> @lang('messages.notes')</h4>
                                                <div class="input-style input-style-2">
                                        <textarea name="notes" class="form-control"
                                                    placeholder="@lang('messages.write_notes')"></textarea>
                                                </div>
                                            @endif
            
            
                                            @if($meal->branch->cart == 'true')
                                                <div class="float-right">
                                                    <div class="input-style input-style-2 mt-0 d-flex">
                                                        <button type="button" data="{{$meal->id}}" class="incress total_sum"
                                                                style="width: 40px; border-radius: 10px; background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important">
                                                            +
                                                        </button>
                                                        <input name="total{{$meal->id}}" type="text" readonly value="1"
                                                            class="border-1 cbox">
                                                        <button type="button" data="{{$meal->id}}" class="incress minn total_min"
                                                                style="width: 40px; border-radius: 10px;">-
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
            
                                            <div class="float-left total-div-{{$meal->id}}" id="total_div"
                                                style="display: {{$meal->price == 0 ? 'none' : 'block'}}">
                                                <div class="content mt-n1 mb-0">
                                                    <h3 class="font-14 color-yellow1-dark sum"
                                                        style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                                        @lang('messages.total')
                                                        <span class="total-span-{{$meal->id}}" id="{{$meal->id}}"
                                                            data="{{$meal->id}}"
                                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                            {{$meal->price}}
                                        </span>
                                                        <span
                                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">
                                            {{app()->getLocale() == 'ar' ? $meal->branch->country->currency_ar : $meal->branch->country->currency_en}}
                                        </span>
                                                    </h3>
            
                                                    @if($meal->branch->total_tax_price == 'true')
                                                        <span class="vatcart"
                                                            style="color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important">@lang('messages.total_tax_price')</span>
                                                    @endif
                                                </div>
                                            </div>
            
            
                                        </div>
            
                                        @if($meal->branch->cart == 'true')
                                            <div class="content text-center mt-2 mb-3 add-to-cart-{{$meal->id}}" id="add_to_cart"
                                                style="display: block;">
                                                <div class="clear"></div>
                                                <div id="additionButton" style="display:block;">
                                                    @if($table != null)
                                                        @php
                                                            $check_table_order = \App\Models\TableOrder::whereStatus('in_reservation')
                                                            ->where('ip' , '!=' , Request::ip())
                                                            ->where('table_id' , $table->id)
                                                            ->first();
                                                        @endphp
                                                        @if($check_table_order == null)
                                                            <button type="submit"
                                                                    style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important; color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important"
                                                                    data-product_id="{{$meal->id}}"
                                                                    class="btn btn-l mb-3 rounded-m text-uppercase font-900 shadow-s bg-dark2-dark btn-save-cart">
                                                                @lang('messages.add_to_cart')
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="submit"
                                                                style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important; color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important"
                                                                data-product_id="{{$meal->id}}"
                                                                class="btn btn-l mb-3 rounded-m text-uppercase font-900 shadow-s bg-dark2-dark btn-save-cart">
                                                            @lang('messages.add_to_cart')
                                                        </button>
                                                    @endif
                                                </div>
                                                {{-- @if(auth()->check() == false)
                                                    @if($table != null )
                                                        <button type="submit"
                                                                style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important; color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important"
                                                                class="btn btn-l mb-3 rounded-m text-uppercase font-900 shadow-s bg-dark2-dark btn-save-cart">
                                                            @lang('messages.add_to_cart')
                                                        </button>
                                                    @else
                                                        <a href="{{route('showUserLogin'  , [$meal->restaurant->id , $meal->branch->id])}}"
                                                        class="btn btn-l mb-3 rounded-m text-uppercase font-900 shadow-s bg-dark2-dark"
                                                        style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important; color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important"
            
                                                        >
                                                            @lang('messages.add_to_cart')
                                                        </a>
                                                    @endif
                                                @else
                                                    <button type="submit"
                                                            style="background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->icons}} !important; color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->main_heads}} !important" data-product_id="{{$meal->id}}"
                                                            class="btn btn-l mb-3 rounded-m text-uppercase font-900 shadow-s bg-dark2-dark btn-save-cart">
                                                        @lang('messages.add_to_cart')
                                                    </button>
                                                @endif --}}
                                            </div>
                                        @endif
                                    </form>
                    </div>
                </div>
            </div>
       </div>
        
        
        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}
        
        
        <script>
        
            function calcPrice(id) {
                var menuProduct = $('#menu-prodact-' + id);
        
                var productPrices = {
                    default_price: parseInt($('#default-price' + id).val()),
                    quantity: 1,
                    sizes: [],
                    options: []
                };
                // check size
                var sizesitems = menuProduct.find('.size_price');
                $.each(sizesitems, function (k, v) {
                    var item = $(v);
                    if (item.prop('checked') == true) productPrices.sizes.push({
                        id: item.prop('value'),
                        price: item.data('price')
                    });
                });
        
                // chcek option item
                var optionPrices = menuProduct.find('.activity_price');
                $.each(optionPrices, function (k, v) {
                    var item = $(v);
                    if (item.prop('checked') == true) {
                        var quantity = item.parent().parent().find('.addition');
                        if (quantity.length) var qty = parseInt(quantity.prop('value'));
                        else var qty = 1;
                        productPrices.options.push({
                            id: item.data('id'),
                            price: item.data('price'),
                            quantity: qty
                        });
                    }
                });
                // check quantity
        
                var quantity = $('input[name=total' + id + ']');
                if (quantity.length) {
                    productPrices.quantity = parseInt(quantity.prop('value'));
                } else productPrices.quantity = 1;
                // calc total price
        
                var totalPrice = 0;
                // size
                if (productPrices.sizes.length > 0) {
                    $.each(productPrices.sizes, function (k, size) {
                        totalPrice += size.price;
        
                    });
                    console.log('size price : ' + totalPrice);
        
                    // options
                    $.each(productPrices.sizes, function (k, size) {
                        $.each(productPrices.options, function (k, option) {
                            totalPrice += option.price * option.quantity;
                        });
                    });
                    // quantity
                    totalPrice = totalPrice * productPrices.quantity;
        
                } else {
                    totalPrice = productPrices.default_price;
                    // quantity
                    totalPrice = totalPrice * productPrices.quantity;
                    // options
                    $.each(productPrices.options, function (k, option) {
                        totalPrice += option.price * option.quantity;
                    });
                }
                //   console.log(productPrices);
        
        
                $('.total-span-' + id).html(totalPrice);
                console.log('totalPrice : => ' + totalPrice);
            }
        
            $(document).ready(function () {
                'use strict'
                var files = '';
                var final = '';
                var totalSizePrice = 0;
                $('.activity_price').click(function () {
                    var mealId = $(this).attr('data');
                    var tag = $(this);
                    if(tag.data('choose_one') == true){
                        var items = tag.parent().parent().parent().parent().find('.activity_price');
                        $.each(items , function(k , v){
                            var t = $(v);
                            if(tag.data('id') != t.data('id') && t.data('main_id') == tag.data('main_id')){
                                t.prop('checked' , false);
                            }
                        });
                    }
                    var old = parseInt(document.getElementById(mealId).textContent);
                    if ($(this).prop("checked") == true) {
                        // document.getElementById('additionButton').style.display = 'block';
                        var optionId = $(this).attr('data-id');
                        var files = parseInt(document.getElementById(optionId + mealId).textContent);
                        final = old + files;
                    } else if ($(this).prop("checked") == false) {
                        var optionId = $(this).attr('data-id');
                        var files = parseInt(document.getElementById(optionId + mealId).textContent);
                        // document.getElementById('additionButton').style.display = 'none';
                        final = old - files;
                    }
                    if ({{$meal->id}} == mealId) {
                        document.getElementById(mealId).textContent = final;
                    }
                    calcPrice(mealId);
                });
        
        
                $('.size_price').click(function () {
        
                    var tag = $(this);
        
                    var productId = tag.data('product_id');
                    calcPrice(productId);
        
                    $('.total-div-' + productId).css('display', 'block');
                    $('.add-to-cart-' + productId).css('display', 'block');
                    var mealId = $(this).attr('data');
                    var mealPrice = parseInt($(this).attr('data-id'));
        
                    var inputs = $('#menu-prodact-' + productId + ' input.size_price:checked');
                    var files = 0;
                    $.each(inputs, function (key, value) {
                        var item = $(value);
                        files += parseInt(item.prop('value'));
        
                    });
                    totalSizePrice = files;
                    final = files;
        
                    if ($(this).prop("checked") == true) {
                        console.log('checked');
                        $('.total-div-' + productId).css('display', 'block');
                        $('.add-to-cart-' + productId).css('display', 'block');
        
                        var checkboxes = document.getElementsByClassName("activity_price");
                        for (const checkbox of checkboxes) {
                            checkbox.checked = false;
                        }
                        // document.getElementsByClassName("activity_price").checked = false;
                        // get the meal original price
                        // final = final - mealPrice;
                        $('.content-size-' + tag.data('size')).html('<input type="hidden" value="' + tag.data('size') + '"  class="size-price-id  hdnsizeId-{{$meal->id}}" name="size_price_id[]">');
                    } else if ($(this).prop("checked") == false) {
                        files = parseInt((this.value));
                        if ($('#menu-prodact-' + productId + ' input.size_price:checked').length == 0) {
                            // $('.total-div-' + productId).css('display', 'none');
                            // $('.add-to-cart-' + productId).css('display', 'none');
                        }
                        $('.content-size-' + tag.data('size')).html('');
                        // final = final - old;
                        // final = final + mealPrice;
                    }
                    if ({{$meal->id}} == mealId) {
        
                        $('.total-span-' + productId).html(final);
                        // $('hdnsizeId-' + productId).textContent = files;
        
        
                    }
                    calcPrice(mealId);
                });
        
                $('form.silver-cart-form').submit(function () {
                    return false;
                });
            });
        
        </script>
        
        
        <script>
            $(function () {
                $('[data-src]').lazy();
                $('.menu.menu-box-bottom .close-menu , .menu-hider').on('click', function () {
                    $('#xcategories').addClass('fixedIndex-3').removeClass('fixedIndex-2');
                    $('#footer-bar').addClass('fixedIndex-3').removeClass('fixedIndex-2');
                    console.log('fixed index -3');
                });
            });
        </script>
        
        <style>
            .clowind {
                top: 7px !important;
            }
        
            .product-details {
                position: relative;
            }
        
            .product-details .header {
                position: sticky;
                top: 0;
                left: 0;
                z-index: 999;
                box-shadow: none;
                margin: 0;
                padding: 20px 15px 20px 15px;
                background-color: {{$meal->restaurant->color == null ? '' : $meal->restaurant->color->product_background}} ;
            }
        
            .product-details .menu-box-bottom.menu-box-detached .card {
                min-height: 400px !important;
                background-color: #EEE;
            }
        
            .product-details .menu-box-bottom.menu-box-detached .card img {
                min-height: 400px;
            }
        
            :root {
                --rest-background-color: {{$meal->restaurant->color == null ? '#fff' : $meal->restaurant->color->product_background}} ;
            }
        
        </style>
        
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

@endpush

@include('website.'.session('theme_path').'silver.layout.scripts')
