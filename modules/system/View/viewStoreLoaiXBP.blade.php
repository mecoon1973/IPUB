@extends('page::layout')

@php
    $pageProps = [
        "loaiXBP" => $loaiXBP,
    ];
@endphp

@section('content')
    <div id="root-store-loai-xbp" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/LoaiXBP/viewStoreLoaiXBP.tsx')
@endpush
