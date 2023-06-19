@if($restaurant->sliders->count() > 0)
    @foreach($restaurant->sliders as $slider)
        @if($slider->type == 'youtube' )
        <div id="slider-{{$slider->id}}">
           
            {{-- <iframe style="width:100%;" class="close-menu"  src="https://www.youtube.com/embed/{{$slider->youtube}}"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
        </div>
        @elseif($slider->type == 'image')
        <div>
            <div class="card shadow-xl" data-card-height="210" style="background-image: url({{asset('/uploads/sliders/' . $slider->photo)}});background-size: cover;    background-repeat: no-repeat;">
                <div class="card-overlay "></div>
                <div class="card-overlay"></div>
            </div>
        </div>
        @endif
    @endforeach
@endif


@push('scripts')
<script>
       function onYouTubeIframeAPIReady(videoId , tagId) {
                 console.log('interval');
                 
                 window.YT.ready(function() { 
                        console.log($(tagId));
                        var player = new YT.Player(tagId, {
                            height: '200',
                            width: '100%',
                            videoId: videoId,
                            playerVars: {
                                'playsinline': 1 , 
                                'fs' :  1 , 
                                'disablekb' : 1 , 
                                'rel' : 0 , 
                                'showinfo': 0, 
                                'ecver': 2
                            },
                            events: {
                                'onReady': onPlayerReady,
                                'onStateChange': onPlayerStateChange
                            }
                        });
                    
                 
                   
                    console.log('done');
                     
                 });
                 
                 
           
        }

        // 4. The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            console.log('ready video');
            // event.target.playVideo();
        }

        // 5. The API calls this function when the player's state changes.
        //    The function indicates that when playing a video (state=1),
        //    the player should play for six seconds and then stop.
        var done = false;
        function onPlayerStateChange(event) {
            console.log('change : ' + event.data);
            
        }
     
        
    $(function(){
        @foreach($restaurant->sliders as $slider)
            @if($slider->type == 'youtube')
            setTimeout(() => {
                onYouTubeIframeAPIReady("{{$slider->youtube}}" , 'slider-{{$slider->id}}');
            }, 1);
            @endif

        @endforeach
     
        
    });
</script>
    
@endpush
