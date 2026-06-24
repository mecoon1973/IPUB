@extends('page::layout')

@php
    $pageProps = [
        "loaiXbpLc" => $loaiXbpLc,
        "listDonviLC" => $listDonviLC,
    ];
@endphp

@section('content')
    <div id="root-store-loai-xbp-lc" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/LoaiXBPLC/viewStoreLoaiXbpLc.tsx')
@endpush
