@extends('page::layout')

@php
    $pageProps = [
        "phieuDkDetai" => $phieuDkDetai,
        "mapTrangThai" => $mapTrangThai,
        "listMangsach" => $listMangsach,
        "listDoituong" => $listDoituong,
        "listLop" => $listLop,
        "listMonhoc" => $listMonhoc,
        "listBosach" => $listBosach,
        "listTusach" => $listTusach,
        "listDonvi" => $listDonvi,
        "Donvi" => $Donvi,
        "listBTV" => $listBTV,
    ];
@endphp

@section('content')
    <div id="root-store-phieu-dk-detai" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkDetai/viewStorePhieuDkDetai.tsx')
@endpush
