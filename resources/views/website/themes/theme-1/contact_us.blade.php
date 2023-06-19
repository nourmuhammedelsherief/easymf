@include('website.'.session('theme_path').'silver.layout.header')


	<style>
		body{
			background-color: #f5f5f5 !important;
			background-color: #ebeef1 !important;
		}
		#page-1{
			clear: both;
		}
		.page-header{
			padding-top: 60px;
			position: relative;
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
			background-color: #FFF;
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
		<div class="log-container">
			<img src="{{asset($restaurant->image_path)}}" alt="">
		</div>
		<h1 class="text-center mt-3">{{ $restaurant->name }}</h1>
		<p class="text-center description px-2">{!! $restaurant->description !!}</p>
		<div class="share-btn"><img src="{{asset('images/image.svg')}}" style="width:20px; height:20px; " alt=""></div>
	</div>

	<div class="page-body">
		@foreach ($restaurant->contactUsItems as $item)
			<a class="item" href="{{$item->url}}" target="_blank">
				<div class="image">
					<img src="{{asset($item->image)}}" alt="">
				</div>
				<div class="description">{{$item->title}}</div>
				
			</a>
		@endforeach
	</div>

</div>

@include('website.'.session('theme_path').'silver.layout.footer')
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
