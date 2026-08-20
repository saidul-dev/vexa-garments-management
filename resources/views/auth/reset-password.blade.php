@extends('layouts.auth.app', [
    'title' => __('Reset Password')
])

@section('main_content')
    <div class="mybazar-login-section">
        <div class="mybazar-login-wrapper">
            <div class="login-wrapper">
                <h2>Set New Password</h2>
                <h6>Create new password, it must be Strong password.</h6>
                <form action="{{ route('password.store') }}" method="post" class="ajaxform_instant_reload">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ $request->email }}">

                    <div class="input-group">
                        <span><img src="{{ asset('assets/images/icons/lock.png') }}" alt="img"></span>
                        <span class="hide-pass">
                            <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                            <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                        </span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="New Password">
                    </div>
                    <div class="input-group">
                        <span><img src="{{ asset('assets/images/icons/lock.png') }}" alt="img"></span>
                        <span class="hide-pass">
                            <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                            <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                    </div>
                    <button type="submit" class="btn login-btn submit-btn">{{ __('Continue') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
