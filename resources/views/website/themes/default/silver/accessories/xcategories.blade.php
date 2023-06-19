<style>
    .item.active .card {
        border: 4px solid {{$restaurant->color == null ? '#f7b538' : $restaurant->color->icons }}  !important;
    }
    .fixed-categories{
        position: fixed;
        top: 0;
        z-index: 3;
        width: 400px;
        background-color:  {{$restaurant->color == null ? '#FFF'  : $restaurant->color->background }}  !important;

    }
    .card.xproducts.top-190{
        margin-top: 190px;
    }
    .fixedIndex-3{
        z-index: 3 !important;
    }
    .fixedIndex-2{
        z-index: 2 !important;
    }
    /* .item.active:before {
        background: {{$restaurant->color == null ? '#f7b538' : $restaurant->color->icons }}  !important;
    } */
</style>
@if($restaurant->color == null)
    <div id="box" class="text-center my-categories">

        @else
            <div id="box" class="text-center my-categories"
                 style="overflow-x:scroll;background-color: {{$restaurant->color == null ? '' : $restaurant->color->background }} !important">
                @endif
                <?php
                $menu_categories = $restaurant->menu_categories()->where('branch_id' , $branch->id)
                    ->orderBy(DB::raw('ISNULL(arrange), arrange' , 'id'), 'ASC')
                    ->get();
                        
                ?>
                @if($menu_categories->count() > 0 && $menu_category != null)

                    @php
                        $catCount = 0;
                    @endphp
                        <div class="splide">
                            <div class="splide__track">
                                    <div class="splide__list">
                                        
                                        
                                            @foreach($menu_categories as $index => $category)

                                            {{-- end add current category --}}
                                            @if($category->active == 'true')
                                                @if($category->time == 'false')
                                                    @php
                                                        $catCount++;
                                                    @endphp

                                                    @if($table == null)
                                                        @php
                                                            $route = route('sliverHome', [$restaurant->name_barcode  , $category->id  , $branch->main == 'true' ? null : $branch->name_barcode]);
                                                        @endphp
                                                    @else
                                                        @php
                                                            $route = route('sliverHomeTable', [$restaurant->name_barcode ,$table->foodics_id != null ? $table->foodics_id : $table->name_barcode , $category->id  , $branch->main == 'true' ? null : $branch->name_barcode]);
                                                        @endphp
                                                    @endif
                                                    <div class="splide__slide" >
                                                        <div class="itemCat menu-category-{{$category->id}}  {{$menu_category->id == $category->id ? 'active' : ''}}" data-sort="{{$category->arrange}}"  
                                                            data-count="{{$index}}" data-id="{{$category->id}}">
                                                            <a href="javascript:;" data-link="{{$route}}">
                                                                <div class="item {{$menu_category->id == $category->id ? 'active' : ''}} bg-theme rounded-s "
                                                                    style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->category_background}} !important">
                                                                    <div data-card-height="120" class="card mb-0 bg-29 rounded-s"
                                                                        style="overflow: hidden;"
                                                                    >
                                                                        @if($category->foodics_image != null and $category->photo == null)
                                                                            <img
                                                                                src="{{$category->foodics_image}}"
                                                                                style="max-width:100%;    height: 100%;"/>
                                                                        @else
                                                                            <img
                                                                                src="{{empty($category->photo) ? asset($restaurant->image_path) : asset($category->image_path)}}"
                                                                                style="max-width:100%;    height: 100%;"/>
                                                                        @endif

                                                                    </div>
                                                                    <h5 class="card-bottom color-black"
                                                                        style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                                        {{app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en}}
                                                                    </h5>
                                                                </div>
                                                            </a>
                                                        </div>

                                                    </div>
                                                


                                                @elseif(check_time_between($category->start_at , $category->end_at))
                                                <div class="splide__slide"  >
                                                    <div class="itemCat  menu-category-{{$category->id}}" data-count="{{$index}}"
                                                        data-id="{{$category->id}}">
                                                        @php
                                                            $catCount++;
                                                        @endphp
                                                        @if($table == null)
                                                            @php
                                                                $route = route('sliverHome', [$restaurant->name_barcode  , $category->id  , $branch->main == 'true' ? null : $branch->name_barcode]);
                                                            @endphp
                                                        @else
                                                            @php
                                                                $route = route('sliverHomeTable', [$restaurant->name_barcode ,$table->foodics_id != null ? $table->foodics_id : $table->name_barcode , $category->id  , $branch->main == 'true' ? null : $branch->name_barcode]);
                                                            @endphp
                                                        @endif
                                                        <a href="javascript:;" data-link="{{$route}}">
                                                            <div
                                                                class="item {{$menu_category->id == $category->id ? 'active' : ''}} bg-theme rounded-s"
                                                                style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->category_background}} !important">
                                                                <div data-card-height="120" class="card mb-0 bg-29 rounded-s rounded-s"
                                                                    style="overflow: hidden;"
                                                                >
                                                                    @if($category->foodics_image != null)
                                                                        <img
                                                                            src="{{$category->foodics_image}}"
                                                                            style="max-width:100%;    height: 100%;"/>
                                                                    @else
                                                                        <img
                                                                            src="{{empty($category->photo) ? asset($restaurant->image_path) : asset($category->image_path)}}"
                                                                            style="max-width:100%;    height: 100%;"/>
                                                                    @endif
                                                                </div>
                                                                <h5 class="card-bottom color-black"
                                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                                    {{app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en}}
                                                                </h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                {{-- @else

                                                        {{$category->name_ar}} --}}
                                            @endif
                                        @endforeach
                                    </div>
                            </div>
                            
                        </div>
                          

                    
                @else
                    <h3>
                        @lang('messages.no_categories')
                    </h3>
                @endif
            </div>


            <div class="text-center menu-category-name">
                <h5 style="color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important">
                    {!! $menu_category == null ? '' :  (app()->getLocale() == 'ar' ? strip_tags($menu_category->name_ar) : strip_tags($menu_category->name_en)) !!}
                </h5>
            </div>


            <script>
                // $(function () {
                //     var activeItem = $('.itemCat.active');
                //     var categories = $('.my-categories');
                //     if (activeItem.data('count') > 1) {
                //         var position = -activeItem.outerWidth(true) * (activeItem.data('count') - 1) + 18;
                //     } else var position = 0;

                //     categories.scrollLeft(position);
                // });
            </script>

            @push('styles')

                <style>
                    .item.active:before {
                        background: {{$restaurant->color == null ? '' : $restaurant->color->icons }}  !important;
                    }

                </style>


    @endpush

