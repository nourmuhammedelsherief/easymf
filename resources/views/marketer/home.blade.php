@extends('marketer.lteLayout.master')

@section('title')
    @lang('messages.marketers_control_panel')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('messages.welcome') {{\Illuminate\Support\Facades\Auth::guard('marketer')->user()->name}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">@lang('messages.control_panel')</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>
                                {{$operations = \App\Models\MarketerOperation::whereMarketerId(\Illuminate\Support\Facades\Auth::guard('marketer')->user()->id)->where('status' , 'done')->count()}}
                            </h3>
                            <p> @lang('messages.confirmed_operations') </p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fa fa-balance-scale-right"></i>
                        </div>
                        <a href="{{route('confirmed_operations')}}" class="small-box-footer"> @lang('messages.details') <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <!--<div class="col-lg-3 col-6">-->
                    <!-- small box -->
                <!--    <div class="small-box bg-success">-->
                <!--        <div class="inner">-->
                <!--            <h3> {{$operations = \App\Models\MarketerOperation::whereMarketerId(\Illuminate\Support\Facades\Auth::guard('marketer')->user()->id)->where('status' , 'not_done')->count()}} </h3>-->
                <!--            <p>@lang('messages.not_confirmed_operations')</p>-->
                <!--        </div>-->
                <!--        <div class="icon">-->
                <!--            <i class="nav-icon fa fa-balance-scale-left"></i>-->
                <!--        </div>-->
                <!--        <a href="{{route('not_confirmed_operations')}}" class="small-box-footer">@lang('messages.details') <i class="fas fa-arrow-circle-right"></i></a>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ \App\Models\MarketerTransfer::whereMarketerId(\Illuminate\Support\Facades\Auth::guard('marketer')->user()->id)->count()}}</h3>

                            <p>@lang('messages.bank_transfers')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-money-check-alt"></i>
                        </div>
                        <a href="{{route('transfersMarketer')}}" class="small-box-footer"> @lang('messages.details') <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <?php
                            $tc = \App\Models\MarketerOperation::whereMarketerId(\Illuminate\Support\Facades\Auth::guard('marketer')->user()->id)->where('status' , 'done')->sum('amount');
                            $tt = \App\Models\MarketerTransfer::whereMarketerId(\Illuminate\Support\Facades\Auth::guard('marketer')->user()->id)->sum('amount');
                            ?>
                            <h6> @lang('messages.total_commission') :  {{$tc}}</h6>
                            <h6> @lang('messages.total_transfers') :  {{$tt}}</h6>
                            <h6> @lang('messages.restBalance') :  {{ $tc-$tt }}</h6>

                            <p> @lang('messages.myAccount') </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
{{--                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
