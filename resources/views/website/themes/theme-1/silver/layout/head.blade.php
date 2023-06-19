<div class="header header-fixed header-auto-show header-logo-app"  style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->background}}">
    @guest
        @if($table == null)
            <a href="{{route('showUserLogin' , [$restaurant->id , $branch->id])}}" class="header-title header-subtitle" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                <i class="fas fa-user"></i>
            </a>
        @endif

    @else
        <a href="#" data-menu="menu-profile" class="header-title header-subtitle" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
            {{\Illuminate\Support\Facades\Auth::guard('web')->user()->phone_number}}
            <i class="fas fa-angle-down"></i>
        </a>
    @endguest

    <a href="#" data-menu="menu-map" class="header-title header-subtitle" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
        <i class="fas fa-map-marker-alt"></i>
        {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}}
    </a>
    @if($restaurant->ar == 'true' && $restaurant->en == 'true')
        @if(app()->getLocale() == 'en')
            <a href="#" class="header-title header-subtitle" onclick="window.location='{{ route('language' , 'ar') }}'" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                ع
            </a>
            {{--            <a type="button" onclick="window.location='{{ route('language' , 'ar') }}'">ع</a>--}}
        @else
            <a href="#" class="header-title header-subtitle" onclick="window.location='{{ route('language' , 'en') }}'" style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}}">
                En
            </a>
            {{--            <button type="button" onclick="window.location='{{ route('language' , 'en') }}'">En</button>--}}

        @endif
    @endif
</div>

{{--5105105105105100--}}
