@extends('page::layout')
@php
    $pageProps = [
        "PhieuChuyenBanThao" => $PhieuChuyenBanThao,
    ];
@endphp

@section('content')
    <div id='root-store-phieu-chuyen-ban-thao' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/book/view/PhieuChuyenBanThao/viewStorePhieuChuyenBanThao.tsx')
@endpush
