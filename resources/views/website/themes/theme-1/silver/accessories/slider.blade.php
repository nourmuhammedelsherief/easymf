@if($restaurant->sliders->count() > 0)
    @foreach($restaurant->sliders as $slider)
        <div>
            <div class="card shadow-xl" data-card-height="210" style="background-image: url({{asset('/uploads/sliders/' . $slider->photo)}});background-size: cover;    background-repeat: no-repeat;">
                <div class="card-overlay "></div>
                <div class="card-overlay"></div>
            </div>
        </div>
    @endforeach
@endif
