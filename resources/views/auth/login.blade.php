@extends('layouts.auth.app', [
    'title' => __('Login')
])

@section('main_content')
<div class="mybazar-login-section">
        <div class="mybazar-login-wrapper">
            <div class="login-wrapper">
                <h2>Welcome to <span>{{ env('APP_NAME') }}</span></h2>
                <h6>Please login in to your account</h6>
                <form method="POST" action="{{ route('login') }}" class="ajaxform_instant_reload">
                    @csrf
                    <div class="input-group">
                        <span>
                            <img src="{{ asset('assets/images/user-img/user.png') }}" alt="img">
                        </span>
                        <input type="email" name="email" class="form-control email" placeholder="Enter your Username">
                    </div>

                    <div class="input-group">
                        <span><img src="{{ asset('assets/images/icons/lock.png') }}" alt="img"></span>
                        <span class="hide-pass">
                            <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                            <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                        </span>
                        <input type="password" name="password" class="form-control password" placeholder="Enter Password">
                    </div>

                    <div class="mt-lg-3 mb-0 forget-password">
                        <label>
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}">{{ ('Forgot Password?') }}</a>
                    </div>

                    <button type="submit" class="btn login-btn submit-btn">{{ __('Login') }}</button>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" data-model="Login" id="auth">
@endsection

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush

