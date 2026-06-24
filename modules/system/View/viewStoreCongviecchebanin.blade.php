@extends('page::layout')

@php
    $pageProps = ["congviecchebanin" => $congviecchebanin];
@endphp

@section('content')
    <div id="root-store-congviecchebanin" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    /@vite('resources/ts/modules/system/view/Congviecchebanin/viewStoreCongviecchebanin.tsx')
@endpush
