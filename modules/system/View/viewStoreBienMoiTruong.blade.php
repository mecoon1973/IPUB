@extends('page::layout')

@php
    $pageProps = ["bienMoiTruong" => $bienMoiTruong];
@endphp

@section('content')
    <div id="root-store-bien-moi-truong" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/BienMoiTruong/viewStoreBienMoiTruong.tsx')
@endpush
