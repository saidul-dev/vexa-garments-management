@extends('layouts.auth.app', [
    'title' => __('Login')
])

@section('main_content')
<div class="mybazar-login-section">
    <div class="login-container">

        <div class="login-intro">
            <div class="login-brand">
                <span class="login-brand-icon"><img src="{{ asset('assets/images/logo/logo.svg') }}" alt="{{ env('APP_NAME') }}"></span>
                <span class="login-brand-text">{{ strtoupper(__('Unified ERP for Garment Manufacturing & Export')) }}</span>
            </div>

            <h1 class="login-headline">{{ __('Sign in.') }}</h1>
            <p class="login-subline">{{ __('Every order. Every shipment.') }} <strong>{{ __('One system.') }}</strong></p>
            <p class="login-description">
                {{ __('The operational backbone for garment manufacturers — from booking and costing to production, shipment, accounts, and HR, all in one platform.') }}
            </p>

            <div class="login-features">
                <span class="login-features-label">{{ __("What's Inside") }}</span>
                <div class="login-pill-list">
                    <span class="login-pill">{{ __('Orders, Costing & Budgets') }}</span>
                    <span class="login-pill">{{ __('Samples & Bookings') }}</span>
                    <span class="login-pill">{{ __('Production & Shipments') }}</span>
                    <span class="login-pill">{{ __('Accounts & Reports') }}</span>
                    <span class="login-pill">{{ __('Party & Accessory Management') }}</span>
                    <span class="login-pill">{{ __('HRM') }}</span>
                </div>
            </div>

            <div class="login-footer-credit">
                &copy; {{ date('Y') }} {{ env('APP_NAME') }} &middot; {{ __('Enterprise Suite') }} &middot; {{ __('Powered by') }} <strong>Vexasoft</strong>
            </div>
        </div>

        <div class="login-wrapper">
            <span class="login-welcome-back">{{ __('Welcome Back') }}</span>
            <h2>{{ __('Sign in') }}</h2>

            <form method="POST" action="{{ route('login') }}" class="ajaxform_instant_reload">
                @csrf
                <div class="form-group">
                    <label>{{ __('Email') }}</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control email" placeholder="{{ __('Enter your email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ __('Password') }}</label>
                    <div class="input-group">
                        <span class="hide-pass">
                            <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="hide">
                            <img src="{{ asset('assets/images/icons/show.svg') }}" alt="show">
                        </span>
                        <input type="password" name="password" class="form-control password" placeholder="{{ __('Enter password') }}">
                    </div>
                </div>

                <div class="mt-lg-3 mb-0 forget-password">
                    <label>
                        <input type="checkbox" name="remember">
                        <span>{{ __('Remember me') }}</span>
                    </label>
                    <a href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                </div>

                <button type="submit" class="btn login-btn submit-btn">{{ __('Sign in') }}</button>
            </form>

            @if(app()->environment('local'))
                <div class="login-quickfill">
                    <span class="login-quickfill-label">{{ __('Quick Fill · Dev Only') }}</span>
                    <div class="button-group">
                        @php
                            $quickFillRoles = ['Super Admin', 'Admin', 'Manager', 'Buyer', 'Customer', 'Supplier', 'Merchandiser', 'Commercial', 'Accountant', 'Production'];
                        @endphp
                        @foreach($quickFillRoles as $roleName)
                            @php
                                $slug = str($roleName)->remove(' ')->lower();
                                $demoEmail = $slug.'@'.$slug.'.com';
                            @endphp
                            <button type="button" class="login-btn-group" data-email="{{ $demoEmail }}" data-password="{{ $slug }}">{{ $roleName }}</button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
<input type="hidden" data-model="Login" id="auth">
@endsection

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
