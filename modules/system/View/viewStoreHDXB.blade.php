@extends('page::layout')

@php
    $pageProps = ["hdxb" => $hdxb];
@endphp

@section('content')
    <div id="root-store-hdxb" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    {{-- @vite('resources/ts/modules/system/view/HDXB/viewStoreHDXB.tsx') --}}
@endpush
