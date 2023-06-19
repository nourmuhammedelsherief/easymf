<div class="popup-content">
    <div class="content mx-1 mb-0">
        <div class="popup-header">
            <div class=" mt-n1 mr-3">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu">
                    <i class="fa fa-arrow-down"></i>
                </a>
            </div>
            <h3 class="font-700 text-center"> العروض <!-- @lang('messages.photos') --></h3>
            <div class="divider"></div>
        </div>


        @if($restaurant->offers->count() > 0)
            @foreach($restaurant->offers as $offer)
                @if(check_time_between($offer->start_at , $offer->end_at))
                    <div class="offer-content px-0 clearfix">
                        <div>
                            <div class="  text-center">
                                @if(!empty($offer->photo))
                                    <div class="item bg-theme rounded-m mb-2 shadow-l" data-menu="data-photo-show" data-image="{{asset($offer->image_path)}}">
                                        <div data-card-height="240" class="card mb-0" style="background-image: url({{asset($offer->image_path)}});">

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

</div>


<style>
    .popup-content .content{
        margin: 0;
    }
    .popup-content{
        position: relative;
        /* display: block */
    }
    .popup-content .popup-header{
        position: sticky;
        top: 0;
        left: 0;
        clear: both;
        z-index: 99;
        display: block;

        padding-top: 20px;
        background-color: var(--rest-background-color);
    }
    .offer-content{
        margin: 0 15px;
    }
    .popup-content .divider{
        margin: 0;
        margin-top: 20px;
        /* margin-top: 17px; */
    }
    .popup-content .close-menu{
        position: absolute;
        top: 7px;
    }
</style>
    


