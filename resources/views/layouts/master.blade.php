<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="{{__('IE=edge')}}">
    <meta name="viewport" content="{{__('width=device-width, initial-scale=1.0')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') | @endif{{ config('app.name') }}</title>
    @include('layouts.partials.css')
</head>
<body>

<!-- Side Bar Start -->
@include('layouts.partials.side-bar')
<!-- Side Bar End -->
<div class="section-container">
    <!-- header start -->
    @include('layouts.partials.header')
    <!-- header end -->
    <!-- erp-state-overview-section start -->
    @yield('main_content')
    <!-- erp-state-overview-section end -->
    @stack('modal')
</div>

@include('layouts.partials.script')
</body>
</html>
