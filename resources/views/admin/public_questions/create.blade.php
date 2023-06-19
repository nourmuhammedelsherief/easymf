@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.public_questions')
@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.public_questions') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('public_questions.index')}}">
                                @lang('messages.public_questions')
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
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.add') @lang('messages.public_questions') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('public_questions.store')}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.question_ar') </label>
                                    <input name="question" type="text" class="form-control" value="{{old('question_ar')}}" placeholder="@lang('messages.question')">
                                    @if ($errors->has('question'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('question') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- question en --}}
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.question_en') </label>
                                    <input name="question_en" type="text" class="form-control" value="{{old('question_en')}}" placeholder="@lang('messages.question_en')">
                                    @if ($errors->has('question_en'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('question_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body">
                                    {{-- answer ar --}}
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.answer_en') </label>
                                        <textarea class="form-control textarea" name="answer"
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                        @if ($errors->has('answer'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('answer') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    {{-- answer en --}}
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.answer_en') </label>
                                        <textarea class="form-control textarea" name="answer_en"
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                        @if ($errors->has('answer_en'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('answer_en') }}</strong>
                                        </span>
                                        @endif
                                    </div>
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
@endsection

@section('scripts')
<script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection

