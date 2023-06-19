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

		max-height: 150px;
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
		transition: 0.3s ease;
	}
	.list-times .time-card:hover , 
	.list-times .time-card.active{
		/* border: 1px solid #CCC; */
		box-shadow: 1px 2px 10px #CCC;
		transition: 0.3s ease;
	}
	
	.list-times .time-card .time-card-header{
		padding:10px 0 0 10px;
		background-color: #ebebeb;
		direction: rtl;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
		transition: 0.3s ease;
	}
	.list-times .time-card:hover .time-card-header , 
	.list-times .time-card.active .time-card-header{
		background-color: #c1c1c1;
		transition: 0.3s ease;
	}
	.footer-button{
		margin-top: 40px;
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
</style>




<div class="x-card-header"></div>
<div class="x-card card mr-0 ml-0 rounded-l" style="">
	<div class="container">
		<h1 class="text-center" style="font-size: 1.1rem !important;margin-top: 15px;">{{ trans('messages.reservations') }} - {{$restaurant->name}}</h1>
		@if($errors->any())
			<p class="alert alert-danger mt-5">{{$errors->first()}}</p>
		@endif
		@if(auth('web')->check())
			<div class="branches">

				<a href="#" data-menu="menu-map"
					class="">
					<p>{{ trans('messages.choose_branch') }}</p>
					@if(isset($branch))
					<i class="fas fa-map-marker-alt"
						style="color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"></i>
					
						<span>{{$branch->name}}</span>
						@endif
				</a>
			</div>
			
				@if(isset($branch) and isset($canReservation) and $canReservation)
			
					<div class="calander">
						<div id="calendar" style="width:100% !important;"></div>
					</div>
					<div class="places clearfix" id="places" style="clear: both;" >
						
						<p class="text-center mt-5">{{ trans('messages.choose_date') }}</p>
					</div>
						
					
					<div class="footer-button text-center">
						<a href="javascript:;" class="btn btn-primary save-page">{{ trans('messages.next_step') }}</a>
						<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-secondary " >{{ trans('messages.return') }}</a>
					</div>
				
				@elseif((isset($canReservation) and !$canReservation )or (isset($dates) and count($dates) == 0) )
				
					{{-- error message  --}}
					<h4 class="text-center alert alert-info">{{ trans('messages.no_date_reservations') }}</h4>
					<div class="footer-button text-center">
						<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
						
					</div>
				@else 
					{{-- error message  --}}
					{{-- <h4 class="text-center alert alert-info">{!! trans('messages.choose_branch_here') !!}</h4> --}}
					<div class="footer-button text-center">
						<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
						
					</div>
				@endif
			<form action="{{route('reservation.page1' , $restaurant->id)}}" method="post" id="reservationForm">
				@csrf
				<input type="hidden" name="branch_id" value="{{ $branchId }}">
				<input type="hidden" name="date">
				<input type="hidden" name="period_id">
			</form>
			
		@else 

			<h4 class="text-center alert alert-info mt-5">{{ trans('messages.login_required') }}</h4>
			<div class="footer-button text-center">
				<a href="{{route('sliverHome' , $restaurant->name_barcode)}}" class="btn btn-primary">{{ trans('messages.to_to_restaurant') }}</a>
				<a href="{{route('showUserLogin' , $restaurant->id)}}" class="btn btn-primary">{{ trans('messages.login') }}</a>
			</div>
		@endif 
		{{-- end auth web --}}
	</div>

</div>



<div class="footer footer-description text-center">
	{!!  trans('messages.reservation_footer') !!}
</div>
<div id="menu-map"
	style="border-top-left-radius: 15px;border-top-right-radius: 15px;"
     class="menu menu-box-bottom menu-box-detached"
     data-menu-height="300"
     data-menu-effect="menu-over">


    <div class="popup-content mb-0">
        <div class="popup-header" style="padding: 10px 0;">
            <div class="float-right mt-n1 mr-3" style="margin-top: 3px !important;">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                    <i class="fa fa-arrow-down" ></i>
                </a>
            </div>
            <h3 class="font-700 text-center" style="
			margin-bottom: 20px;
			margin-top: 9px;"> @lang('messages.branches')</h3>
            <div class="divider"></div>
        </div>
        @include('website.'.session('theme_path').'reservations.branches')
    </div>
</div>

@push('scripts')
{{-- <script src="{{asset('plugins/color-calender/bundle.min.js')}}"></script> --}}
<script src="{{asset('plugins/vanilla-calendar/vanilla-calendar.min.js')}}"></script>
	<script>
		$(function(){
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
			$('#places').on('click' , '.list-times .time-card' , function(){
				var tag = $(this);
				tag.parent().find('.time-card').removeClass('active');
				tag.addClass('active');
				$('input[name=period_id]').prop('value' , tag.data('id'));
			});

			$('.save-page').on('click' ,function(){
				$('#reservationForm').submit();
			});
		});

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
					var content = '';
					$.each(json.places , function(key , place){
						content += '<div class="place">\
							<h3>'+place.name+'</h3>\
								<div class="list-times">'
							
								$.each(place.periods  , function(k1 , period){
									var tableCount = period.table_count - period.orders_count;
									if(tableCount > 0){
										content += '<div class="time-card" data-id="'+period.id+'">\
											<div class="time-card-header text-center">'+period.from_string+' @lang('dashboard.to') '+period.to_string + '</div>\
											<div class="time-card-body text-center">\
												'+period.people_count+'\
												<i class="fas fa-user-alt"></i>\
												<span>'+period.price+' {{$country->currency}}</span>\
											</div>\
											<p class="text-center">{{trans('dashboard.available')}} '+tableCount+' {{trans('dashboard.tables')}}</p>\
											<p class="text-center" style="font-size:10px !important;">('+period.branch_name+')</p>\
										</div>';
										
									}
								});
							
						content += '</div>\
						</div>';
					});
					$('#places').html(content);
					$('input[name=period_id]').prop('value' , null);
				},
				error: function(xhr){
					console.log(xhr);
				}
			});
		}
	</script>
@endpush

@include('website.'.session('theme_path').'silver.layout.scripts')
