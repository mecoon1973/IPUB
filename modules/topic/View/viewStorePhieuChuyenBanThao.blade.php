@extends('page::layout')
@php
    $pageProps = [
        'PhieuChuyenBanThao' => $PhieuChuyenBanThao,
        'listMangsach' => $listMangsach ?? [],
        'listDonvi' => $listDonvi ?? [],
        'listBTV' => $listBTV ?? [],
    ];
@endphp

@section('content')
    <div id="root-store-phieu-chuyen-ban-thao" data-props='@json($pageProps)'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuChuyenBanThao/viewStorePhieuChuyenBanThao.tsx')
@endpush