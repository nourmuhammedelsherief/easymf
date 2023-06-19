<div class="list-group list-custom-small px-4">
    @if($restaurant->deliveries->count() > 0)
        @foreach($restaurant->deliveries as $delivery)
            <a href="{{$delivery->link}}" target="_blank">
                <span class="font-16 font-600">
                    {{app()->getLocale() == 'ar' ? $delivery->name_ar : $delivery->name_en}}
                </span>
                <i><img src="{{asset('/uploads/deliveries/' . $delivery->icon)}}" height="40" width="60" /></i>
            </a>
        @endforeach
    @endif
</div>
