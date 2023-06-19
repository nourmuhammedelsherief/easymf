<div class="prodcontent card  mt-5 mb-0"
     style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->background }} !important">
    <div class="row px-3">
        <?php
        $count = 0;
        $menu_categories = \App\Models\MenuCategory::whereRestaurantId($restaurant->id)
            ->where('branch_id', $branch->id)
            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
            ->get();
        ?>
        @if($menu_categories->count() > 0)
            @foreach($menu_categories as $category)
                @if($category->active == 'true')
                    <?php $count++ ?>
                    @if($category->time == 'false')
                        <div class="col-md-4 col-6">
                            <div class="card card-style mx-0 zoominc"
                                 style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->category_background}} !important">
                                @if($branch->main == 'true')
                                    <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id])}}"
                                       style="overflow: hidden;">
                                        @if($category->foodics_image != null and $category->photo == null)
                                            <img src="{{$category->foodics_image}}"
                                                 style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                 class="img-fluid">
                                        @else
                                            @if($category->photo != null)
                                                <img src="{{asset($category->image_path)}}"
                                                     style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                     class="img-fluid">
                                            @else
                                                <img src="{{asset('/uploads/restaurants/logo/'.$restaurant->logo)}}"
                                                     class="img-fluid">
                                            @endif
                                        @endif

                                    </a>
                                    <div class="px-1 py-2 text-center vircentertitle">
                                        <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id])}}">
                                            <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                {{app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en}}
                                            </h3>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id , $branch->name_barcode])}}"
                                       style="overflow: hidden;">
                                        @if($category->foodics_image != null and $category->photo == null)
                                            <img src="{{$category->foodics_image}}"
                                                 style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                 class="img-fluid">
                                        @else
                                            @if($category->photo != null)
                                                <img src="{{asset($category->image_path)}}"
                                                     style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                     class="img-fluid">
                                            @else
                                                <img src="{{asset('/uploads/restaurants/logo/'.$restaurant->logo)}}"
                                                     class="img-fluid">
                                            @endif
                                        @endif
                                    </a>
                                    <div class="px-1 py-2 text-center vircentertitle">
                                        <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id , $branch->name_barcode])}}">
                                            <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                {{app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en}}
                                            </h3>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @elseif(check_time_between($category->start_at , $category->end_at))
                        <div class="col-md-4 col-6">
                            <div class="card card-style mx-0 zoominc"
                                 style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->category_background}} !important">
                                @if($branch->main == 'true')
                                    <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id])}}"
                                       style="overflow: hidden;">
                                        @if($category->foodics_image != null and $category->photo == null)
                                            <img src="{{$category->foodics_image}}"
                                                 style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                 class="img-fluid">
                                        @else
                                            @if($category->photo != null)
                                                <img src="{{asset($category->image_path)}}"
                                                     style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                     class="img-fluid">
                                            @else
                                                <img src="{{asset('/uploads/restaurants/logo/'.$restaurant->logo)}}"
                                                     class="img-fluid">
                                            @endif
                                        @endif
                                    </a>
                                    <div class="px-1 py-2 text-center vircentertitle">
                                        <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id])}}">
                                            <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                {{app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en}}
                                            </h3>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id , $branch->name_barcode])}}"
                                       style="overflow: hidden;">
                                        @if($category->foodics_image != null and $category->photo == null)
                                            <img src="{{$category->foodics_image}}"
                                                 style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                 class="img-fluid">
                                        @else
                                            @if($category->photo != null)
                                                <img src="{{asset($category->image_path)}}"
                                                     style="height:111px;width:100%;object-fit: cover;transition: .5s;"
                                                     class="img-fluid">
                                            @else
                                                <img src="{{asset('/uploads/restaurants/logo/'.$restaurant->logo)}}"
                                                     class="img-fluid">
                                            @endif
                                        @endif
                                    </a>
                                    <div class="px-1 py-2 text-center vircentertitle">
                                        <a href="{{route('sliverHome', [$restaurant->name_barcode , $category->id , $branch->name_barcode])}}">
                                            <h3 style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                {{app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en}}
                                            </h3>
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        @else
            <div class="col-12 mb-2 text-center">
                <h5 style="color: orange">
                    @lang('messages.no_categories')
                </h5>
            </div>
        @endif
    </div>
</div>
