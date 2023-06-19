@if($menu_category != null)
    @if($menu_category->active == 'true')
        @if($menu_category->time == 'true')
            @if(check_time_between($menu_category->start_at , $menu_category->end_at))
                <div>
                    <div class="col-12 mb-2 text-center">
                        <p class="font-14 color-theme mb-1"
                           style="color: {{$menu_category->restaurant->color == null ? '' : $menu_category->restaurant->color->options_description}} !important">
                            @if($menu_category->description_ar || $menu_category->description_en)
                                <a data-toggle="modal" data-target="#myModal-{{$menu_category->id}}">
                                    {!! app()->getLocale() == 'ar' ? \Illuminate\Support\Str::limit($menu_category->description_ar,30) : \Illuminate\Support\Str::limit($menu_category->description_en , 30) !!}
                                </a>
                        <div class="modal" id="myModal-{{$menu_category->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">
                                            {{app()->getLocale() == 'ar' ? 'وصف القسم' : 'Menu Category Description'}}
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        {!! app()->getLocale() == 'ar' ? $menu_category->description_ar : $menu_category->description_en !!}
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">@lang('messages.close')</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                        </p>
                    </div>

                    <div>
                        <div class="double-slider5 owl-carousel text-center  mb-2">
                            @if($menu_category != null)
                                @if($menu_category->sub_categories->count() > 0)
                                    @foreach($menu_category->sub_categories  as $sub)
                                        <div class="item ">
                                            <div data-card-height="35"
                                                 class=" pr-3 card mb-0 bg-theme rounded-s  bord-all2 {{$subCat == $sub->id ? 'active' : ''}}"
                                                 style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
                                                <a class="{{$subCat == $sub->id ? 'active' : ''}}" href="javascript:;"
                                                   data-link="{{route('sliverHome', [$restaurant->name_barcode , $menu_category->id , $branch->name_barcode, $sub->id])}}">
                                                    <div class="card-center mb-0 ">

                                                        <label style="cursor: pointer; " class="color-dark1-dark " style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                            {{app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en}}

                                                            {{-- <span
                                                                style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                                {{app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en}}
                                                            </span> --}}
                                                        </label>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div>
                <div class="col-12 mb-2 text-center">
                    <p class="font-14 color-theme mb-1"
                       style="color: {{$menu_category->restaurant->color == null ? '' : $menu_category->restaurant->color->options_description}} !important">
                        @if($menu_category->description_ar || $menu_category->description_en)

                            @if(checkWordsCount($menu_category->description , 14 , true))
                                {{getShortDescription($menu_category->description , 0 , 14 , true)}} ... 
                                <a href="javascript:;" data-toggle="modal" class="btn-custom-modal" data-target="#longDescription">{{ trans('messages.more') }}</a>


                                <!-- Modal -->
                                <div class="modal custom-modal fade" id="longDescription" tabindex="-1" role="dialog" aria-labelledby="longDescriptionTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="longDescriptionTitle">{{ trans('messages.category_description') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                        {!! $menu_category->description !!}
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('messages.close') }}</button>
                                     
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @else 
                            {!! $menu_category->description !!}
                            @endif

                    @endif
                    </p>
                </div>

                <div>
                    <div class="double-slider5 owl-carousel text-center mb-2">
                        @if($menu_category != null)
                            @if($menu_category->sub_categories->count() > 0)
                                @foreach($menu_category->sub_categories  as $sub)
                                    <div class="item">
                                        <div data-card-height="35"
                                             class=" pr-3 card mb-0 bg-theme rounded-s  bord-all2 {{$subCat == $sub->id ? 'active' : ''}}"
                                             style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
                                            <a class="{{$subCat == $sub->id ? 'active' : ''}}"
                                               href="javascript:;"
                                               data-link="{{route('sliverHome', [$restaurant->name_barcode , $menu_category->id, $branch->name_barcode , $sub->id])}}">
                                                <div class="card-center mb-0">

                                                    <label style="cursor: pointer;" class="color-dark1-dark " style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                        {{app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en}}
                                                        {{--
                                                                                                                <span
                                                                                                                    style="color: {{$restaurant->color == null ? '' : $restaurant->color->main_heads}} !important">
                                                                                                                    {{app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en}}
                                                                                                                </span> --}}
                                                    </label>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                @endforeach
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif
<script type="text/javascript" src="{{asset('themes-assets/theme-1/scripts/custom.js')}}"></script>

