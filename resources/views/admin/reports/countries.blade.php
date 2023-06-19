@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.control_panel')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('messages.control_panel')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">@lang('messages.control_panel')</a></li>
                        {{--                        <li class="breadcrumb-item active">Dashboard v1</li>--}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <h3 class="text-center"> @lang('messages.restaurant_countries_reports') </h3>
            <br>
            <div class="row">
                @foreach($countries as $country)
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-silver">
                            <div class="inner">
                                <h3>
                                    {{app()->getLocale() == 'ar' ? $country->name_ar: $country->name_en }}
                                </h3>
                                <p>@lang('messages.cities_count')  : <a class="btn btn-info">{{$country->cities->count()}}</a></p>
                                <p>
                                    {{app()->getLocale() == 'ar' ? 'المطاعم النشطة' : 'Active' }} : <a class="btn btn-success">{{$country->restaurants()->whereStatus('active')->count()}}</a>
                                    {{app()->getLocale() == 'ar' ? 'المطاعم الغير نشطه' : 'Not Active' }} : <a class="btn btn-danger">{{$country->restaurants()->where('status','!=','active')->count()}}</a>
                                </p>
                            </div>
                            <div class="icon" style="color: black">
                                <i class="fa fa-building"></i>
                            </div>
                            <a href="{{url('/admin/countries_cities/'.$country->id)}}" style="color: black"
                               class="small-box-footer">@lang('messages.details')
                                <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
