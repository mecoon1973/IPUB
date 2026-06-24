@extends('page::layout')

@php
    $pageProps = [];
@endphp

@section('content')
    <div id="root-store-qd-in" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/QDIn/viewStoreQDIn.tsx')
@endpush
