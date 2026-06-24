@extends('page::layout')
@php
    $pageProps = [
        'doituongSNV' => $doituongSNV,
        'listLoaiSNV' => $listLoaiSNV,
    ];
@endphp

@section('content')
    <div id='root-store-doituong-snv' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/DoituongSNV/viewStoreDoituongSNV.tsx')
@endpush
