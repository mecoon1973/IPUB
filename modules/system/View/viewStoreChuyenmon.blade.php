@extends('page::layout')

@php
    $pageProps = [
        "chuyenmon" => $chuyenmon,
    ];
@endphp

@section('content')
    <div id="root-store-chuyenmon" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Chuyenmon/viewStoreChuyenmon.tsx')
@endpush
