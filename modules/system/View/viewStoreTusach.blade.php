@extends('page::layout')

@php
    $pageProps = [
        "tusach" => $tusach,
    ];
@endphp

@section('content')
    <div id="root-store-tusach" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Tusach/viewStoreTusach.tsx')
@endpush
