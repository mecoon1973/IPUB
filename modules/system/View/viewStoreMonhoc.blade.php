@extends('page::layout')

@php
    $pageProps = [
        "monhoc" => $monhoc,
    ];
@endphp

@section('content')
    <div id="root-store-monhoc" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Monhoc/viewStoreMonhoc.tsx')
@endpush
