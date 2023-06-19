<div class="list-group list-custom-small px-4">
   
    @if($restaurant->socials->count() > 0)
        @foreach($restaurant->socials as $social)
            <a href="{{$social->link}}" target="_blank">
                <span
                    class="font-16 font-600"> {{app()->getLocale() == 'ar' ? $social->name_ar : $social->name_en}} </span>

                <i><img src="{{asset('/uploads/socials/' . $social->icon)}}" height="40" width="40"></i>

            </a>
            <!-- AddToAny END -->
        @endforeach
    @endif

</div>
