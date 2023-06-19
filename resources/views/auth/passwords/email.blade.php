@extends('layouts.master')

@section('content')
<div class="container">
    <?php $ad4 = \App\Models\Ad::inRandomOrder()->first();?>
    @if($ad4 != null)
        <div class="ads container">
            <a target="_blank" href="{{$ad4->link}}">
                <img src="{{asset('/uploads/advertisements/' . $ad4->photo)}}" style="width:100%"/>
            </a>
        </div>
    @endif
    <div class="card">
        <div class="card-header text-center" > نسيت كلمة المرور </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <div class="form-group ">
                            <label class="control-label"> البريد الألكتروني </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-sm-2">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-4">

                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-3">
                        <div class="form-actions">
                            <button type="submit" class="btn green" value="تسجيل دخول" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();"> أرسال رابط أستعاده كلمة المرور </button>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            </form>
        </div>
    </div>
    <?php $ad3 = \App\Models\Ad::inRandomOrder()->first();?>
    @if($ad3 != null)
        <div class="ads container">
            <a target="_blank" href="{{$ad3->link}}">
                <img src="{{asset('/uploads/advertisements/' . $ad3->photo)}}" style="width:100%"/>
            </a>
        </div>
    @endif
</div>

@endsection
