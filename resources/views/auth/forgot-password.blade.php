@extends('layouts.auth.app', [
    'title' => __('Forget Password')
])

@section('main_content')
    <div class="mybazar-login-section">
        <div class="mybazar-login-wrapper">
            <div class="login-wrapper">
                <h2>Forgot Password</h2>
                <h6>Enter the email address associated with your account</h6>
                <form method="POST" action="{{ route('password.email') }}" class="ajaxform_instant_reload">
                    @csrf
                    <div class="input-group">
                        <span><img src="{{ asset('assets/images/icons/envelope.png') }}" alt="img"></span>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your Email">
                    </div>

                    <button type="submit" class="btn login-btn submit-btn">{{ __('Continue') }}</button>
                </form>
                <div class="back-to-login">
                    <img src="{{ asset('assets/images/user-img/user.png') }}" alt="img">
                    <a href="{{ route('login') }}">{{ ('Back to Login') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

