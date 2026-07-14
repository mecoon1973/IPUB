@extends('page::layout')

@php
    $pageProps = [
        'listUsers' => $listUsers,
        'mapTrangThai' => $mapTrangThai,
        'listDonvi' => $listDonvi,
        'listMangsach' => $listMangsach,
        'phieuDkKhxbCxb' => $phieuDkKhxbCxb,
        'listDeTai' => $listDeTai,
    ];
@endphp

@section('content')
    <div id="root-store-phieu-dk-khxb-cxb" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkKhxbCxb/viewStorePhieuDkKhxbCxb.tsx')
@endpush
