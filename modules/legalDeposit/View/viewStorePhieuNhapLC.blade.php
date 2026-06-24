@extends('page::layout')

@php
    $pageProps = [
        "phieuNhapLC" => $phieuNhapLC,
    ];
@endphp

@section('content')
    <div id="root-store-phieu-nhap-lc" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/legalDeposit/view/PhieuNhapLC/viewStorePhieuNhapLC.tsx')
@endpush
