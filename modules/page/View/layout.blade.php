@php
    $usingHeader = $usingHeader ?? true;
@endphp

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titleHeaderPage', 'iPub - Hệ thống quản lý xuất bản')</title>
    <link rel="icon" href="{{ asset('svg/favicon.svg') }}" type="image/svg+xml">

    @stack('meta')
</head>
@include('page::render-layout-head')
@if ($usingHeader)
    {{-- biuld reactjs header component --}}
    @viteReactRefresh
    @vite('resources/ts/modules/page/component/header/Header.tsx')
@endif

<body class="">
    <div id="root-header"></div>
    <div class="main-wrap">
        @yield('content')
    </div>
    <div class="main-footer">
        @yield('footer')
    </div>
    {{-- toast: vị trí top/bottom do JS (toastbox) gán --}}
    <div
        class="toast-container position-fixed p-3 d-flex flex-column gap-2"
        style="z-index: 10000; right: 0; left: auto;"
        id="toast-container"
    ></div>


    @stack('extra')

    
    @include('components.extra-main-layout')

    @include('page::render-layout-js')

</body>
</html>
