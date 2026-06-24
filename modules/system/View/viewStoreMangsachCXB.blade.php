@extends('page::layout')

@php
    $pageProps = [
        "mangsachCXB" => $mangsachCXB,
    ];
@endphp

@section('content')
    <div id="root-store-mangsach-cxb" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/MangsachCXB/viewStoreMangsachCXB.tsx')
@endpush
