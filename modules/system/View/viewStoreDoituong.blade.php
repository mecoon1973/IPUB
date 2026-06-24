@extends('page::layout')

@php
    $pageProps = ["doituong" => $doituong];
@endphp

@section('content')
    <div id="root-store-doituong" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Doituong/viewStoreDoituong.tsx')
@endpush
