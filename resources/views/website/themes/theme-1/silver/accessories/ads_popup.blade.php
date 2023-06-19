{{-- ads --}}
@php
    $currentDate = date('Y-m-d');
    if(isset($menu_category->id) and isset($restaurant->id) and Request::segment(3) != null):
        $ads = \App\Models\RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'menu_category')->where('category_id' , $menu_category->id)->where('start_date' , '<=' , $currentDate)->where('end_date' , '>' , $currentDate)->orderBy('created_at' , 'desc')->get();
    elseif(isset($restaurant->id)):
        if(isset($menu_category->id) and !$ads = \App\Models\RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'menu_category')->where('category_id' , $menu_category->id)->where('start_date' , '<=' , $currentDate)->where('end_date' , '>' , $currentDate)->orderBy('created_at' , 'desc')->get()):

            $ads = \App\Models\RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'main')->where('start_date' , '<=' , $currentDate)->where('end_date' , '>' , $currentDate)->orderBy('created_at' , 'desc')->get();
        else:
            $ads = \App\Models\RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'main')->where('start_date' , '<=' , $currentDate)->where('end_date' , '>' , $currentDate)->orderBy('created_at' , 'desc')->get();
             
        endif;
        
        
    endif;
    if(isset($ads) and count($ads) > 0):
        foreach($ads as $temp):
            if($temp->isTime()):
                $ads = $temp;
                break;
            endif;
        endforeach;
    endif;
    if(!isset($ads->id)) $ads = null;
@endphp
@if(isset($ads->id) and $ads->isAllow())
    <div id="menu-ad"
        data-menu="menu-ad"
        class="menu ad-menu menu-box-bottom menu-box-detached rounded-m "
        {{-- data-menu-height="310" --}}
        data-menu-width="320"
        data-menu-effect="menu-over">

        <div class="content mb-0">
            <div class="xicon close-menu" style="">
                <a href="#"
                class="close-menu" style="width: 38px;
                height: 38px;
                background-color: #2c0977 !important;
                display: inline-block;
                text-align: center;
                border-radius: 50%;
                padding-top: 9px;margin-top:5px;margin-bottom:5px;">
                    <i class="fa fa-times"  style="font-size:1rem;color:#FFF;"></i>
                </a>
            </div>
            
        
            
            @if($ads->content_type == 'image')
                <div class="image-preview close-menu" >
                    <img src="{{asset($ads->image_path)}}" style="width: 100%;" alt="">
                </div>
            @elseif($ads->content_type == 'youtube')
                <iframe style="width:100%" class="close-menu"  src="{{$ads->content}}?autoplay=1"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @endif
            <div class="footer-content" style="padding: 0px;">
                <form action="{{route('ads.not_allowed')}}" method="POST" id="not_ads" style="height:25px;">
                    @csrf
                <input type="checkbox" class="item" name="not_allowed_ads_id" id="not_allowed_ads" value="{{$ads->id}}">
                <label for="not_allowed_ads" class="item" style="height:21px;">{{ trans('messages.not_allowed_ads') }}</label>
            </form>
            </div>
        </div>
    </div>
	

<script>
	$(function(){
		// not ads 
        
		$('#not_ads .item').on('click', function(){
			
			var input = $('#not_allowed_ads');
			if(input.prop('checked') == true) input.prop('checked' , false);
			else input.prop('checked' , true);
			$.ajax({
				url : "{{route('ads.not_allowed')}}" , 
				method : 'POST' , 
				// headers : {
				//     Accept : "application/json" , 
				// },
				data : {
					not_allowed_ads_id : input.prop('value') , 
					_token : "{{csrf_token()}}"
				 },
				 success :function(json){
					console.log(json);
					$('#menu-ad .close-menu').trigger('click');
				 },
				 error: function (xhr){
					console.log(xhr);
				 },
			});
		});
	});
</script>

@endif