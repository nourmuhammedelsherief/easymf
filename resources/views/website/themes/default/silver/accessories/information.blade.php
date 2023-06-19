<p class="pb-2 pr-4 pl-4 font-14 text-justify" style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">
    {!! app()->getLocale() == 'ar' ? $restaurant->information_ar : $restaurant->information_en !!}
</p>
