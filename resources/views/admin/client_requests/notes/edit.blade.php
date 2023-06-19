@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.client_request_notes')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @lang('messages.edit') @lang('dashboard.note') 
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item ">
                            <a href="{{route('admin.client_request.index' )}}">
                                @lang('dashboard.client_requests')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.client_request.note.index' , $clientRequest->id)}}">
                                @lang('dashboard.client_request_notes')
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
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.note') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('admin.client_request.note.store'  , $clientRequest->id )}}/{{$note->id}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            <input type="hidden" name="_method" value="PUT">
                            <div class="card-body">
                                
                              
                                <div class="form-group">
                                    <label class="control-label"> @lang('dashboard.description') </label>
                                    <textarea name="description" type="text" class="form-control"  placeholder="@lang('dashboard.description')" required>{{$note->description}}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('description') }}</strong>
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
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
