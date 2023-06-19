@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-center" > استعادة كلمة المرور </div>

        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <div class="form-group ">
                            <label class="control-label"> البريد الألكتروني </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <label class="control-label"> كلمة المرور </label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <label class="control-label">أعادة كلمة المرور </label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

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
                            <button type="submit" class="btn green" value="تسجيل دخول" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();"> أستعاده كلمة المرور </button>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
