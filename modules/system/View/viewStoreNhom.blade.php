@extends('page::layout')

@php
    $pageProps = [
        "nhom" => $nhom,
    ];
@endphp

@section('content')
    <div id="root-store-nhom" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Nhom/viewStoreNhom.tsx')
@endpush
