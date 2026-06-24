@extends('page::layout')

@php
    $pageProps = [
        "toKhaiLuuChuyen" => $toKhaiLuuChuyen,
    ];
@endphp

@section('content')
    <div id="root-store-to-khai-luu-chuyen" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/legalDeposit/view/PhieuNhapLC/viewStoreToKhaiLuuChuyen.tsx')
@endpush
