<div class="product-details package-details" style="padding-bottom:70px;">
    {{-- <div class="content header pt-2 pb-4">




    </div> --}}
    <div class="card  shape-rounded rounded-lp"
         style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important">

        <div class="prodsld" style="position: relative">
            <a href="#" class="chip chip-small bg-black2 close-menu clowind"
               style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;position:absolute;top:0;left:10px;">
                <i class="fa fa-times bg-yellow1-dark "
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"></i>

            </a>
            <div>
                <div class="card shadow-xl">
                    @if($table->image != null)
                        
					<div class="splide">
						<div class="splide__track">
								<div class="splide__list">
									{{-- first slider --}}
										<div class="splide__slide"  >
											<div class="image-prview" style="height:250px;width:100%;text-align:center;">
												<img src="{{$table->image_path}}" alt="" style="max-width:100%;height:100%">
											</div>
										</div>
										@foreach ($table->images as $item)
											<div class="splide__slide"  >
												<div class="image-prview" style="height: 300px;">
													<img src="{{asset($item->path)}}" alt="" style="width:100%; height: 100%;">
												</div>
											</div>
										@endforeach
									
								</div>
						</div>
						
					</div>
                @endif
              
                <!--  <div class="card-overlay "></div>
                      <div class="card-overlay"></div>-->
					</div>
                    <h4 class="mb-1 font-20 product-title"
						style="padding:0 20px;color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
						{{app()->getLocale() == 'ar' ? $table->title_ar : $table->title_en}}
					</h4>
					
					
					

            </div>

        </div>


    </div>


    <div>

        <div class="rounded-lp mt-n2 pl-3  pr-3"
             style="position: relative; background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">


            <div class="content pt-4">

				<div class="details">
					{!! $table->description !!}
				</div>	

                <div class="clearfix">
                    <div class="text-center">
                        <span class="price"><span data-price="{{$table->price }}">{{$table->chair_min > 0 ? $table->price * $table->chair_min : $table->price}}</span> {{$country->currency}}</span>
                                                <span class="people_count"><span class="">{{$table->people_count * $table->chair_min}}</span> <i class="fas fa-user-alt"></i> </span>
                    </div>
                    <div class="text-center">{{$period->from_string}} الي {{$period->to_string}}</div>
                    <div class="text-center" style="line-height:1;margin-bottom:20px;">{{trans('dashboard.remaining')}} {{$quantity}}</div>
                    <div class="text-center">
                        <div class="quantity-chairs" data-min="{{$table->chair_min}}"  data-max="{{$quantity}}">
                            <button type="button" class="btn plus">+</button>
                            <input type="text" readonly name="qty" data-type="package" data-people_count="{{$table->people_count}}" value="{{$table->chair_min == null ? 1 : $table->chair_min}}">
                            <button type="button" class="btn sub">-</button>
                        </div>
                    </div>
                     
                </div>
            </div>
           
        </div>
    </div>
    
<div class="buttons">
	<button class="btn btn-primary" id="saveReservation">{{ trans('messages.choose') }}</button>
	<button class="btn btn-secondary close-menu" id="close">{{ trans('messages.close') }}</button>
</div>
</div>

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}



<script>
    $(function () {
        $('[data-src]').lazy();
 
		var splide = new Splide( '.splide', {
				type     : 'loop',
				// height   : '10rem',
				// focus    : 'right',
				//  autoplay: 'pause',
				direction : 'rtl' , 
				autoplay: 'play',
				perPage : 1,
				interval : 3000,
				autoWidth: false,
		} );
			// splide.resolve('right');
			splide.mount();

        $('#saveReservation').on('click' , function(){
            $('#reservationForm').submit();
        });
        console.log('check new')
        $('body').on('change' , '.package-details input[name=qty]' , function(){
            var tag = $(this);
            console.log(tag.val());
        });
    });
</script>

<style>
	.buttons{
		position: absolute;
    	bottom: -45px;
		width: 100%;
        padding-bottom: 30px;
		text-align: center;
	}
	/* .buttons .btn{
		display: flex;
	} */
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
        background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} ;
    }

    .product-details .menu-box-bottom.menu-box-detached .card {
        min-height: 400px !important;
        background-color: #EEE;
    }

    .product-details .menu-box-bottom.menu-box-detached .card img {
        min-height: 400px;
    }
    .product-details .people_count{
        margin: 0 10px;
    }
    .product-details input[name=qty]{
        height: 30px;
        width: 52px !important;
        line-height: 4px;
    }
    .product-details .quantity-chairs button{
        width: 30px;
        height: 30px;
    }
    :root {
        --rest-background-color: {{$restaurant->color == null ? '#fff' : $restaurant->color->product_background}} ;
    }

</style>

