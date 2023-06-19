@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.arrange') @lang('messages.menu_categories')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.arrange') @lang('messages.menu_categories') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('menu_categories.index')}}">
                                @lang('messages.arrange') @lang('messages.menu_categories')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.add') @lang('messages.menu_categories') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('arrangeSubmitMenuCategory' , $category->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type="hidden" name="image_name" value="">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.arrange') </label>
                                    <select name="arrange" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @for($i=1; $i <= 50 ; $i++)
                                            <?php $check = \App\Models\MenuCategory::whereArrange($i)
                                                ->where('branch_id' , $category->branch_id)
                                                ->where('restaurant_id' , $category->restaurant_id)
                                                ->where('id' , '!=', $category->id)
                                                ->first();
                                            ?>
                                            @if($check == null)
                                                 <option value="{{$i}}" {{$i == $category->arrange ? 'selected' : ''}}> {{$i}} </option>
                                            @endif
                                        @endfor
                                    </select>
                                    @if ($errors->has('arrange'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('arrange') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    @php
        // $itemId = $product->id ;
        $imageUploaderUrl = route('restaurant.menu_category.update_image');
    @endphp
    @include('restaurant.products.product_image_modal')
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script type="text/javascript">

        function yesnoCheck() {
            if (document.getElementById('yesCheck').checked) {
                document.getElementById('ifYes').style.display = 'none';
            } else {
                document.getElementById('ifYes').style.display = 'block';
            }
        }
    </script>
    <script>
        $("#select-all").click(function(){
            $("input[type=checkbox]").prop('checked',$(this).prop('checked'));
        });
    </script>
@endsection
