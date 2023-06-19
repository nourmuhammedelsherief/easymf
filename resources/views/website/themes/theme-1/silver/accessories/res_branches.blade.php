
<div id="menu-mapsico-list"
     class="menu fixed-header menu-box-bottom menu-box-detached rounded-m"
     data-menu-height="280"
     data-menu-width="320"
     data-menu-effect="menu-over">
     
    <div class="content mb-0">
        <div class="menu-box-header">
            <div class="float-right m-icon mt-n1 mr-3">
                <a href="#"
                   style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->icons}} !important"
                   class="icon icon-xs rounded-xl color-white bg-highlight close-menu"><i
                        class="fa fa-arrow-down"></i></a></div>
    
            <h3 class="font-700 text-center"> @lang('messages.the_branches') </h3>
        </div>
        <div class="list-group list-custom-small px-4 clearfix">
            @if($restaurant->res_branches->count() > 0)
                @foreach($restaurant->res_branches as $res_branch)
                    <a href="{{$res_branch->link}}" target="_blank">
                        <span class="font-16 font-600">{{app()->getLocale() == 'ar' ? $res_branch->name_ar : $res_branch->name_en}}</span>
                        <i class="fas fa-map-marker-alt font-22" style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></i>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>
<style>
.menu-box-header{
    position: sticky;
    top: 0; 
    right:0;
    padding-top:20px;
    z-index: 1;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background-color : var(--rest-background-color) !important;
}
.menu-box-header > div:first-child{
    margin-top: -0.8rem !important;
}
.menu.fixed-header .content{
    margin: 0px 15px 20px 15px;
}
</style>
