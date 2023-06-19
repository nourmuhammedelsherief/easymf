@push('styles')

{{-- <link rel="stylesheet" href="{{asset('plugins/color-calender/theme-basic.css')}}"> --}}
	<link rel="stylesheet" href="{{asset('plugins/color-calender/theme-glass.css')}}">
	<link rel="stylesheet" href="{{asset('plugins/vanilla-calendar/vanilla-calendar.min.css')}}">
@endpush
@include('website.'.session('theme_path').'silver.layout.header')


<style>
    .branche {
        display: none;
    }
	html{
		background-color: #FFF !important;
	}
    body {
        position: relative;
		/* background-color: #FFF !important; */
		/* margin: 0px !important; */
    }
	a{
		color : #000;
	}
	.x-card-header{
		width:  100%;
		height: 40px;
		background-color: {{$restaurant->color == null ? '' : $restaurant->color->background}}
	}
	.x-card{
		min-height: 400px;
		/* padding-top: 40px; */
		border: 0 !important;
		border-radius: 0 !important;
		border-top-left-radius: 47px !important;
		border-top-right-radius: 47px !important;
		/* background-color: {{$restaurant->color == null ? '' : $restaurant->color->background}}, */
	}
	.x-card .branches{
		margin: 20px 10px 10px 10px;
	}
	.x-card .branches p{
		font-size: 14px ;
		font-weight:  bold;
	}
	.x-card .branches a span{
		font-size: 16px;
		/* font-weight: bold; */
	}
	.x-card .branches a i{
		font-size: 22px;
		margin: 0 5px;
	}
	#color-calendar{
		display: flex
	}
	#color-calendar .color-calendar.glass.color-calendar--small{
		justify-content: center;
		width: 307;
    margin: auto;
	}
	.x-card .container{
		max-width: 3339px;;
	}
	.x-card .quantity{
		margin-top: 20px;
	}
	.x-card .quantity div.title{
		float:right;
		display: inline;
	}
	.x-card .quantity div.title > i{
		font-size: 18px;
	}
	.x-card .quantity div.title > span{
		font-size: 16px;
	}
	.x-card .quantity div.count{
		float : left;
		display: inline;
		position: relative
	}
	.x-card .count a{
		display: inline-block;
		position: relative;
		background: none;
		border: 1px solid #CCC;
		border-radius: 100%;
		width: 20px;
		height: 20px;
		padding: 0;
		margin: 0;
		margin-top: 5px;
	}
	.x-card .count a i{
		font-size: 9px;
		position: absolute;
		font-size: 9px;
		top: 6px;
		right: 4px;
	}
	.x-card .count .num{
		width:20px;
	}
	.x-card .count .num{
		font-size:16px;
		font-weight: bold;

	}
	.places{
		margin-top: 20px;
	}
	.list-times{

		max-height: 180px;
		overflow-x: scroll;
		white-space:nowrap;
	}
	.list-times .time-card{
		cursor: pointer;
		width: 140px;
		display: inline-block;
		background-color: #f7f7f7;
		border-radius: 10px;
		/* border: 1px solid transparent; */
		padding: 0 0 10px 0 ;
		margin: 0 5px;
		border: 2px solid transparent;
		transition: 0.3s ease;
	}
	.list-times .time-card:hover, 
	.list-times .time-card.active, 
	.one-place:hover, .one-place.active{
		border: 2px solid orange;
		box-shadow: 1px 2px 10px #CCC !important;
		transition: 0.3s ease;
	}
	
	.list-times .time-card .time-card-header{
		padding:10px 0 0 10px;
		background-color: #ebebeb;
		direction: rtl;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
		border-radius: 10px;
		transition: 0.3s ease;
	}
	.list-times .time-card:hover .time-card-header , 
	.list-times .time-card.active .time-card-header{
		background-color: #c1c1c1;
		transition: 0.3s ease;
	}
	.footer-button{
		margin-top: 40px;
		margin-bottom: 30px;
	}
	.footer-button a{
		font-size: 14px;
	}
	#calendar{
		direction: ltr !important;
	}

	/* new */
	html{
		position: relative;
	}
	body{
		position: initial !important;
		margin-bottom: 50px !important;
	}
	.footer-description{
		position: absolute;
		padding: 10px 0;
		bottom:10px;
		left: 0;
		width: 100% !important; 
		text-align: center;
	}
	.places .list{
		overflow: auto;
		padding: 15px 0 30px 0;
  		white-space: nowrap;
	}
	.places h2{
		font-size: 16px !important;
		font-weight: normal;
	}
	.one-place{
		display: inline-block;
		gap: 10px;
		width: 180px;
		height: 110px;
		margin: 5px;
		position: relative;
		border-radius: 10px;
		
		box-shadow: 1px 1px 10px #ccc;
		cursor: pointer;
		transition: 0.3s;
	}
	.one-place:hover,
	.one-place.active{
		box-shadow: 1px 1px 15px #181717;
		transition: 0.3s;
	}
	.one-place:first-child{
		margin-right: 0;
	}
	
	.one-place img{
		width: 100%;
		height: 100%;
		border-radius: 10px;
	}
	.one-place .title{
		position: absolute;
		display: flex;
		bottom: 0;
		right: 0;
		width: 100%;
		height: 63px;
		color: #FFF;
		overflow: auto;
		background: linear-gradient(180deg, transparent, #424242);
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
	}
	.one-place .title span{
		display: inline-block;
		align-self: flex-end;
		font-size: 12px;
		margin: 0 5px 5px 5px;
	}
	.tables > .place {
		/* margin-top: 20px; */
	}
	.quantity-chairs {
		text-align: center;
	}
	.quantity-chairs input{
		width: 38px;
		height: 22px;
		border-radius: 20px;
		text-align: center;
		border: 1px solid #CCC;
		outline: none !important;
		box-shadow: none;
	}
	.quantity-chairs button{
		background-color: #d3d3d3;
		width: 25px;
		height: 25px;
		padding: 0;
		
		border-radius: 100%;
	}
	.quantity-chairs button:first-child{
		margin-left:5px;
	}
	.quantity-chairs button:last-child{
		margin-right:5px;
	}
	.branches .row > div:first-child{
		padding-left: 0;
		padding-right:8px;
	}
	.list-times.package{
		max-height: 300px !important;
	}
	.time-card.package{
		background-color: transparent;
		width: 170px;
	}
	.time-card.package .package-header{
		height: 100px;
	}
	.time-card.package .package-header img{
		width: 100%;
		height: 100%;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
	}
	.time-card.package .package-body{
	    padding: 0 10px;
	}
	.time-card.package .package-body h4{
		font-size: 13px !important;
	}
	.time-card.package .package-body  >div:first-child{
		line-height: 1.2;
    	margin-top: 6px;	
	}
	.time-card.package .package-body  .price{
		font-size: 13px;
	}
	.time-card.package .package-body .people_count{
		float:left;
		font-size:13px;
	}
	.time-card.package .package-body  .times > span{

	}
	.time-card.package .package-body  .times .to{
		float:left;
		
	}
	.time-card.package .time-card-header{
		border-radius: 0px;
	}
	.time-card.package{
		border-color: #ebebeb
	}
	.time-card-body > span{
		margin: 0 14px;
	}
	.time-card .remaining{
		color: red;
	}
	.time-card.stock{
		opacity: 0.6;
	}
	.time-card.chair.stock .time-card-body{
		/* min-height: 53px */
	}
	.list-times .time-card.stock:hover{
		border-color: #CCC;
	}
	.time-card.chair .time-card-body sp{
		margin:0 3px;
	}
	
</style>
<div class="x-card-header"></div>
<div class="x-card card mr-0 ml-0 rounded-l" style="">
	<div class="container">
		<div class="image-preview text-center" >
			<img src="{{asset($restaurant->image_path)}}" style="width:100px;height:100px;" alt="">
		</div>
		<h1 class="text-center" style="font-size: 1.1rem !important;margin-top: 15px;">{{ trans('messages.reservations') }} - {{$restaurant->name}}</h1>
		@if($restaurant->reservation_is_call_phone == 'true' || $restaurant->reservation_is_whatsapp == 'true')
			<div id="box" class=" mt-3 mb-n1" style="min-height: 100px;">
				@if($restaurant->reservation_is_call_phone == 'true')
					<div class="icon-user itemCatTop">

						<a href="tel:{{$restaurant->reservation_call_number}}"
						class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
							<i class="fas fa-phone"
							style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
						</a>
						<p class="font-600 font-13 mt-2"
						style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.call')</p>

					</div>
				@endif
				@if($restaurant->reservation_is_whatsapp == 'true')
					<div class="icon-user itemCatTop">

						<a href="https://wa.me/{{$restaurant->reservation_whatsapp_number}}" target="__blank"
						class="shadow-xl icon icon-l rounded-xl color-white icon-border color-yellow2-dark">
							<i class="fab fa-whatsapp"
							style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important;"></i>
						</a>
						<p class="font-600 font-13 mt-2"
						style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">@lang('messages.whatsapp')</p>

					</div>
				@endif
			</div>
		@endif
		@if($errors->any())
			<p class="alert alert-danger mt-5">{{$errors->first()}}</p>
		@endif
		@if($restaurant->reservation_service != 'true')
		<h4 class="text-center alert alert-warning mt-5">{{ trans('messages.reservation_not_available') }}</h4>
			<div class="footer-button text-center">
				@if($restaurant->reservation_to_restaurant == 'true')
					<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
				@endif
				@if(!auth('web')->check())
				<a href="{{route('showUserLogin' , $restaurant->id)}}" class="btn btn-primary">{{ trans('messages.login') }}</a>
				@endif
			</div>
		@elseif(auth('web')->check())
			<div class="branches">
				<div class="row">
				    <div class="col-2" ><p>{{ trans('messages.branch') }}</p></div>
				    <div class="col-10">
				
        				<select name="branch_id" id="branch_id" class="form-control">
        					@foreach ($branches as $item)
        						<option value="{{$item->id}}" {{$branch->id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>	
        					@endforeach
        					
        				</select>
				    </div>
				</div>
			
			</div>
			
				@if(isset($branch) and isset($canReservation) and $canReservation)
			
					<div class="calander">
						<div id="calendar" style="width:100% !important;"></div>
					</div>


					<div class="places clearfix" id="places" style="clear: both;" >
						<h2>{{ trans('messages.choose_place') }}</h2>
						<div class="list clearfix">
							<p class="text-center mt-5">{{ trans('messages.choose_date') }}</p>
						</div>
					</div>



					<div class="tables" id="tables" style="clear:both;">
						
						
					</div>
						
					
					<div class="footer-button text-center">
						<a href="javascript:;" class="btn btn-primary save-page">{{ trans('messages.next_step') }}</a>
						@if($restaurant->reservation_to_restaurant == 'true')
						<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-secondary " >{{ trans('messages.to_to_restaurant') }}</a>
						@endif
					</div>
				
				@elseif((isset($canReservation) and !$canReservation )or (isset($dates) and count($dates) == 0) )
				
					{{-- error message  --}}
					<h4 class="text-center alert alert-info">{{ trans('messages.no_date_reservations') }}</h4>
					<div class="footer-button text-center">
						@if($restaurant->reservation_to_restaurant == 'true')
						<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
						@endif
					</div>
				@else 
					{{-- error message  --}}
					{{-- <h4 class="text-center alert alert-info">{!! trans('messages.choose_branch_here') !!}</h4> --}}
					<div class="footer-button text-center">
						<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
						
					</div>
				@endif
				<div id="popup-package" data-menu="menu-package-details"></div>
			<form action="{{route('reservation.page1' , $restaurant->id)}}" method="post" id="reservationForm">
				@csrf
				<input type="hidden" name="branch_id" value="{{ $branchId }}">
				<input type="hidden" name="date">
				<input type="hidden" name="period_id">
				<input type="hidden" name="quantity">
			</form>
			
		@else 

			<h4 class="text-center alert alert-info mt-5">{{ trans('messages.login_required') }}</h4>
			<div class="footer-button text-center">
				@if($restaurant->reservation_to_restaurant == 'true')
					<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
				@endif
				<a href="{{route('showUserLogin' , $restaurant->id)}}" class="btn btn-primary">{{ trans('messages.login') }}</a>
			</div>
		@endif 
		{{-- end auth web --}}
	</div>

</div>



<div class="footer footer-description text-center">
	{!!  trans('messages.reservation_footer') !!}
</div>
<div id="menu-package-details"
	style="border-top-left-radius: 15px;border-top-right-radius: 15px;"
     class="menu menu-box-bottom menu-box-detached"
     data-menu-height="100%"
     data-menu-effect="menu-over"
	 data-menu-load=""
	 >
	
</div>

@push('scripts')
{{-- <script src="{{asset('plugins/color-calender/bundle.min.js')}}"></script> --}}
<script src="{{asset('plugins/vanilla-calendar/vanilla-calendar.min.js')}}"></script>
	<script>
		var places = null;
		$(function(){
			$('#branch_id').on('change' , function(){
				var tag = $(this);
				window.location.replace("{{route('reservation.page1' , $restaurant->id)}}?branch_id=" + tag.val());
			});
			@if(isset($dates))
				const calendar = new VanillaCalendar('#calendar' , {
					actions: {
						clickDay(event, dates) {
							if(dates[0]){
								$('input[name=date]').prop('value' , dates[0]);
								console.log('teset');
								getPlaces();
							}
						},
						
					},
					settings: {
						lang: 'ar',
						range: {
							enabled: [
								@foreach($dates as $item)
									"{{$item}}" ,
								@endforeach
							],
						} ,
						visibility: {
							weekend: false,
							today: false,
						},
					// selected: {
					// 	year: ,
					// 	month: 7,
					// },
					},
				});
				calendar.init();
			@endif
			$('#tables').on('click' , '.list-times .time-card' , function(){
				var tag = $(this);
				if(!tag.hasClass('stock')){
					$('.list-times .time-card').removeClass('active');
					tag.addClass('active');
					$('input[name=period_id]').prop('value' , tag.data('id'));
					var t = tag.find('.quantity-chairs input');
					
					$('input[name=quantity]').prop('value' ,t.prop('value'));
					console.log('active');
				}
				console.log('click on card');
			});
			// $('.package-details .btn.plus').on(function(){
			// 	console.log('plus');
			// 	var tag = $(this);
			// 	console.log(tag.parent().data());
			// });
			$('body').on('click' , '.quantity-chairs .btn' , function(){
				var tag = $(this);
				var input = tag.parent().find('input');
				var value = parseInt(input.prop('value'));
				if(tag.hasClass('plus')){
					if(value < tag.parent().data('max')){
						value = value + 1;
						input.prop('value' , value);
						
					}
				}else{
					if(value > tag.parent().data('min')){
						var value = value - 1;
						input.prop('value' , value);
					}
				}
				if(input.data('type') == 'package'){
					console.log('price package' , value);
					var count = parseInt(input.data('people_count')) * value;
					input.parent().parent().parent().find('.people_count > span').html(count);
					var priceTag = tag.parent().parent().parent().find('.price span');
					var price = parseInt(priceTag.data('price')) * value ;
					$('input[name=quantity]').prop('value' ,value);
					priceTag.html(price);
				
				}else{
					if(input.data('type') == 'chair')
						input.parent().parent().find('.time-card-body sp').html(value);
					var priceTag = tag.parent().parent().find('.price');
					var price = parseInt(priceTag.data('price')) * value ;
					$('input[name=quantity]').prop('value' ,value);
					priceTag.html(price);
				}
				
			});
			$('#places').on('click' , '.one-place' ,  function(){
				var tag = $(this);
				tag.addClass('active');
				tag.siblings().removeClass('active');
				console.log(tag.data());
				getTables(tag.data('id'));
			});

			$('#tables').on('click' , '.time-card.package' ,  function(){
				var tag = $(this);
				if(!tag.hasClass('stock')){
					$('#menu-package-details').data('menu-load' , '{{url('restaurants/' . $restaurant->id)}}/reservation/package-details/'+tag.data('id')+'/' + tag.data('date'));
					var t = tag.find('.quantity-chairs input');
					console.log(tag.data());
					$('input[name=quantity]').prop('value' ,tag.data('min'));
					$('#menu-package-details').html('');
					
					$('#popup-package').trigger('click');
				}
			});

			$('.save-page').on('click' ,function(){
				if($('input[name=period_id]').val() > 0){
					$('#reservationForm').submit();
				}else{
					toastr.info("{{trans('messages.please_choose_period')}}");
				}
				
			});
		});
		function getTables(placeId){
			var content = '';
			var chairContent = '';
			var packageContent = '' ;
			var totalContent = ''
			$.each(places , function(key , place){
				if(place.id == placeId  ){
					console.log(place);
					var checkTable = false;
					var checkChair = false;
					var checkPackage = false;
					console.log(place);
					// write table content
					content += '<div class="place">\
						<h3 class="">{{trans('messages.reservation_tables')}}</h3>\
							<div class="list-times">'
						
							$.each(place.periods  , function(k1 , period){
								var tableCount = period.table_count - period.orders_count;
								if( period.type == 'table'){
									checkTable = true;
									content += '<div class="time-card '+(tableCount <= 0 ? 'stock' : '')+'" data-id="'+period.id+'">\
										<div class="time-card-header text-center">'+period.from_string+' @lang('dashboard.to') '+period.to_string + '</div>\
										<div class="time-card-body text-center">\
											'+period.people_count+'\
											<i class="fas fa-user-alt"></i>\
											<span>'+period.price+' {{$country->currency}}</span>\
										</div>\
										<p class="text-center remaining">'+(tableCount <=0 ? '{{trans('dashboard.out_of_stock')}}' : '{{trans('dashboard.remaining')}} '+tableCount)+'</p>\
										<p class="text-center" style="font-size:10px !important;">('+period.branch_name+')</p>\
									</div>';
									
								}
							});
						
					content += '</div>\
					</div>';
					if(checkTable)
						totalContent += content;
					
					// end write table content
					// start write chair content
					chairContent = '<div class="place chairs">\
						<h3 class="">{{trans('messages.reservation_chairs')}}</h3>\
							<div class="list-times">'
						
							$.each(place.periods  , function(k1 , period){
								var maxCount = period.chair_max  - parseInt(period.quantity);
								if(period.type == 'chair' ){
									checkChair = true;
									
									
									chairContent += '<div class="time-card chair '+(maxCount <=0 ? 'stock' : '' )+'" data-id="'+period.id+'">\
										<div class="time-card-header text-center">'+period.from_string+' @lang('dashboard.to') '+period.to_string + '</div>\
										<div class="time-card-body text-center">\
											<sp>'+period.chair_min+'</sp><i class="fas fa-user-alt"></i>\
											<span><span class="price" data-price="'+period.price+'">'+(period.price * period.chair_min)+'</span> {{$country->currency}}</span>\
										</div>\
										'+(maxCount <= 0 ? '<p class="text-center remaining">{{trans('dashboard.out_of_stock')}}</p>' : '<div class="quantity-chairs" data-min="'+period.chair_min+'"  data-max="'+maxCount+'">\
											<button type="button" class="btn plus">+</button>\
											<input type="text"  data-type="chair" readonly name="qty" value="'+period.chair_min+'">\
											<button type="button" class="btn sub">-</button>\
										</div>' )+'<p class="text-center remaining">'+(maxCount <=0 ? '{{trans('dashboard.out_of_stock')}}' : '{{trans('dashboard.remaining')}} '+maxCount)+'</p>\
										<p class="text-center" style="font-size:10px !important;">('+period.branch_name+')</p>\
									</div>';
									
								}else{
									
								}
							});
						
					chairContent += '</div>\
					</div>';
					if(checkChair)
						totalContent += chairContent;
						
							packageContent = '<div class="place chairs">\
							<h3 class="">{{trans('messages.reservation_packages')}}</h3>\
								<div class="list-times package">'
							
								$.each(place.periods  , function(k1 , period){
									var maxCount = period.chair_max  - parseInt(period.quantity);
									if(period.type == 'package' ){
										checkPackage = true;
										packageContent += '<div class="time-card package '+(maxCount <= 0 ? 'stock' : '')+'"  data-id="'+period.id+'" data-date="'+period.date+'" data-min="'+(period.chair_min > 0 ?period.chair_min : 1)+'" '+(maxCount <= 0 ? '' : 'data-menu="menu-package-details"')+'>\
											<div class="package-header">\
												<img src="{{asset('uploads/reservation_tables')}}/'+period.image+'" alt="">\
											</div>\
											<div class="time-card-header text-center">'+period.from_string+' @lang('dashboard.to') '+period.to_string + '</div>\
											<h4 style="font-size:14px !important;margin-top:8px;text-align:center;">'+period.title+'</h4>\
										<div class="time-card-body text-center">\
											'+period.people_count+'\
											<i class="fas fa-user-alt"></i>\
											<span>'+period.price+' {{$country->currency}}</span>\
										</div>\
										<p class="text-center remaining">'+(maxCount <= 0 ? '{{trans('dashboard.out_of_stock')}}' : '{{trans('dashboard.remaining')}} '+maxCount+'')+'</p>\
										<p class="text-center" style="font-size:10px !important;">('+period.branch_name+')</p>\
										</div>'; 
									
										
									}
								});
							
						packageContent += '</div>\
						</div>';
						
						if(checkPackage)
							totalContent += packageContent;
						console.log('end');
					if(place.periods.length == 0){
						console.log('not periods');
						$('#tables').html('<p class="text-center">{{trans('messages.reservation_not_have_periods')}}</p>');
					}else{
						$('#tables').html(totalContent);
					}
					
					$('input[name=period_id]').prop('value' , null);
					$('input[name=quantity]').prop('value' , null);
				}
				
			});
		}
		function getPlaces(){
			
			$.ajax({
				url : "{{route('reservation.data' , $branchId)}}" , 
				method: "GET" , 
				data : {
					date : $('input[name=date]').prop('value') , 
					branch_id : {{$branchId}} , 
				} , 
				success: function (json){
					console.log(json);
					places = json.places;
					var content = '';
					$.each(places , function(k , place){
						var image = place.image_path != null ? "{{asset('')}}/" + place.image_path : '{{asset($restaurant->image_path)}}';
						content += '<div class="one-place" data-id="'+place.id+'">\
								<img src="'+image+'" alt="">\
								<div class="title">\
									<span>'+place.name+'</span>\
								</div>\
							</div>';
					});
					if(content == '') $('.places .list').html('	<p class="text-center mt-5">{{ trans('messages.choose_date') }}</p>');
					else 	$('.places .list').html(content);
					
					$('#tables').html('');
					$('input[name=period_id]').prop('value' , null);
					$('input[name=quantity]').prop('value' , null);
				},
				error: function(xhr){
					console.log(xhr);
				}
			});
		}


	</script>
@endpush

@include('website.'.session('theme_path').'silver.layout.scripts')
