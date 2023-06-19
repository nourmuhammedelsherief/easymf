@include('website.'.session('theme_path').'silver.layout.header')


	<style>
		body{
			/* background-color: #f5f5f5 !important;
			background-color: #ebeef1 !important; */
		}
		#page-1{
			clear: both;
		}
		.page-header{
			padding-top: 60px;
			position: relative;
		}
		#change-lang{
			position: absolute;
			top: 17px;
			left: 17px;
			display: flex;

			justify-content: center;
			/* font-size:18px; */
			color : #0f1117 ;
			font-weight: bold;
			cursor: pointer;
			transition: 0.3s ease;
		}
		.page-header .share-btn{
			position: absolute;
			top: 17px;
			right: 17px;
			display: flex;
			width: 40px;
			height: 40px;
			background-color: rgb(242, 242, 242);
			justify-content: center;
			padding-top: 10px;
			border-radius: 100%;
			border: 1px solid rgb(226, 226, 226);
			cursor: pointer;
			transition: 0.3s ease;
		}
		.page-header .share-btn:hover{
			background-color: #eaeaea;
			box-shadow: 1px 1px 10px #CCC;
			transition: 0.3s ease;
		}
		.page-header .share-btn i{
			font-size: 18px;
			color:rgb(79 79 79);


		}
		.page-header .log-container{
			width: 100px;
			height: 100px;
			margin:auto;
		}
		.page-header .log-container img{
			width: 100%;
			height: 100%;
			border-radius: 100%;
		}
		.page-body .item{
			background-color: {{  $restaurant->color != null ? $restaurant->color->product_background : '#FFF'}};
			display: block;
			color : #000;
			border-radius: 10px;
			margin: 20px 10px;
			padding:5px 10px;
			position: relative;
		}
		.page-body .item :hover{

		}
		.page-body .item .image{
			position: absolute;
			top: 8px;
			left: 10px;
			width: 40px;
			height: 40px;
		}
		.page-body .item .image img{
			width: 100%;
			height: 100%;
			border-radius: 10px;
		}
		.page-body .item .description{

			text-align: center;
			/* width: calc(100% - 50px); */
			margin-top: 10px;
			margin-bottom: 10px;
			background-color: transparent;
			font-size: 1.2rem;
			vertical-align: middle;
		}
	</style>

<div id="page-1" >
	
	<div class="page-header">
		<div class="single-slider mainsld owl-carousel">
            @include('website.'.session('theme_path').'silver.accessories.slider')
        </div>

        <div class="content">
            <div class="d-flex mt-n5 site-title">
                <div class="mt-n5">


                            <a href="{{url('/restaurants/'.$restaurant->name_barcode)}}" class=" shadow-xl ">
                                <img src="{{asset('/uploads/restaurants/logo/' . $restaurant->logo)}}"
                                     style="width: 90px; height:90px; position: relative; z-index: 1;border: 1px dashed #f7b538;"/>
                            </a>



                </div>
                <div class="align-self-center pr-3 mt-1 restaurant-title" style="line-height: 23px;">
                    <h5 class="font-600 font-18 mb-1"
                        style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                        {{app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en}}
                    </h5>
                    <span data-menu="menu-restaurant-description" class="color-theme font-400 font-14"
                          style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}} !important;">
                        {!! app()->getLocale() == 'ar' ? strip_tags(\Illuminate\Support\Str::limit($restaurant->description_ar,70)) : strip_tags(\Illuminate\Support\Str::limit($restaurant->description_en , 70)) !!}
                    </span>
                </div>

            </div>
			@include('website.'.session('theme_path').'silver.accessories.ads_popup')
			@if($restaurant->socials->count() == 0 || $restaurant->socials->count() > 0 || $restaurant->deliveries->count() > 0 || $restaurant->sensitivities->count() > 0 || $restaurant->offers->count() > 0 || $restaurant->information_ar != null || $restaurant->information_en != null || $restaurant->res_branches->count() > 0)
			<div id="box" class=" mt-3 mb-n1" style="min-height: 100px;">



				@if($restaurant->enable_feedback == 'true')
					<div class="icon-user itemCatTop">
						<a href="#" data-menu="menu-rate"
						   class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
							<i class="far fa-comment-alt"
							   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
						</a>
						<p class="font-600 font-13 mt-2"
						   style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.restaurant_feedback')</p>
					</div>
				@endif
				@if($restaurant->is_call_phone == 'true')
					<div class="icon-user itemCatTop">

						<a href="tel:{{$restaurant->call_phone}}"
						   class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
							<i class="fas fa-phone"
							   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
						</a>
						<p class="font-600 font-13 mt-2"
						   style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.call')</p>

					</div>
				@endif
				@if($restaurant->is_whatsapp == 'true')
					<div class="icon-user itemCatTop">

						<a href="https://wa.me/{{$restaurant->whatsapp_number}}" target="__blank"
						   class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
							<i class="fab fa-whatsapp"
							   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
						</a>
						<p class="font-600 font-13 mt-2"
						   style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.whatsapp')</p>

					</div>
				@endif
				@if($restaurant->reservation_service == 'true')
					<div class="icon-user itemCatTop">

						<a href="{{route('reservation.page1' , $restaurant->id)}}"
						   class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
							<i class="far fa-ticket-alt"
							   style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
						</a>
						<p class="font-600 font-13 mt-2"
						   style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.reservations')</p>

					</div>
				@endif


			</div>
		@endif
		</div>
		<!--<div class="share-btn"><img src="{{asset('images/image.svg')}}" style="width:20px; height:20px; " alt=""></div>-->

		<a id="change-lang" href="{{url('locale/' .(app()->getLocale() == 'ar' ? 'en' : 'ar'))}}" style="color : {{$restaurant->color == null ? '' : $restaurant->color->options_description}} !important;">
			@if(app()->getLocale() == 'ar') English
			@else عربي
			@endif
		</a>
	</div>
	{{-- <div class="page-header">
		<div class="log-container">
			<img src="{{asset($restaurant->image_path)}}" alt="">
		</div>
		<h1 class="text-center mt-3">{{ $restaurant->name }}</h1>
		<p class="text-center description px-2">{!! $restaurant->description !!}</p>
		<div class="share-btn"><img src="{{asset('images/image.svg')}}" style="width:20px; height:20px; " alt=""></div>
	</div> --}}

	<div class="page-body">
		@if(isset($contact->id))
			<h3 class="text-center" style="color : {{$restaurant->color == null ? '' : $restaurant->color->options_description}} !important;">{{$contact->name}}</h3>
		@endif
		@php
			if(isset($contact->id)):
				$items = $contact->items()->orderBy('sort')->get();
			else:
				$items = $restaurant->contactUsItems()->whereNull('link_id')->orderBy('sort')->get();
			endif;
		@endphp
		@foreach ($items as $item)
			@if($item->status == 'true')
			<a class="item" href="{{$item->url}}" target="_blank">
				<div class="image">
					<img src="{{asset($item->image)}}" alt="">
				</div>
				<div class="description"  style="color : {{$restaurant->color == null ? '' : $restaurant->color->options_description}} !important;">{{$item->title}}</div>

			</a>
			@endif
		@endforeach
	</div>

</div>

@include('website.'.session('theme_path').'silver.layout.footer')
@include('website.'.session('theme_path').'silver.accessories.res_branches')
@include('website.'.session('theme_path').'silver.accessories.related')
@include('website.'.session('theme_path').'silver.layout.scripts')
<script>
	$(function(){
		console.log($(window).height());

		console.log($('#page-1').attr('style' , 'min-height:' + ($(window).height() - 40 ) + "px !important"));

		$('.share-btn').on('click' , function(){
			var url = "{{route('contactUs' , $restaurant->name_barcode)}}";
			if (navigator.share) {
				navigator.share({
					title: '{{$restaurant->name}}',
					url:  url ,
				}).then(() => {
				console.log('Thanks for sharing!');
				})
				.catch(console.error);
			}else{
				console.log('share on web')
			}
		});
	});
</script>
