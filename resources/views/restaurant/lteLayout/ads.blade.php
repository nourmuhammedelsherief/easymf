{{-- ads --}}
@php
    $currentDate = date('Y-m-d');
    $type = checkUrlAdsType();
	echo $type;
	$ads = \App\Models\RestaurantAds::where('to' , 'restaurant')->whereRaw('(type = "'.$type.'" or type = "all")')->where('start_date' , '<=' , $currentDate)->where('end_date' , '>' , $currentDate)->orderBy('created_at' , 'desc')->get();
 
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

<!-- Modal -->
<div class="modal fade" id="adsShow" tabindex="-1" role="dialog" aria-labelledby="adsShowTitle" aria-hidden="true" >
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
	  <div class="modal-content">
		{{-- <div class="modal-header">
		  <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div> --}}
		<div class="modal-body">
		     
            @if($ads->content_type == 'image')
                <div class="image-preview close-menu" >
                    <img src="{{asset($ads->image_path)}}" style="width: 100%;" alt="">
                </div>
            @elseif($ads->content_type == 'youtube')
                <iframe style="width:100%;min-height:300px;" class="close-menu"  src="{{$ads->content}}?autoplay=1"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
	</div>
  </div>



<script>
	$(function(){
		console.log('check ads');
		$('#adsShow').modal('show');
		$('#adsShow').on('click' , function(){
			$('#adsShow').modal('hide');
		});
		// not ads 
        setTimeout(() => {
            // $('#adsShow').trigger('click');
			// $('#adsShow .modal-body').html('');
            console.log('menu ad close ad');
        }, 6000);
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
					// $('#menu-ad .close-menu').trigger('click');
				 },
				 error: function (xhr){
					console.log(xhr);
				 },
			});
		});
	});
</script>

@endif