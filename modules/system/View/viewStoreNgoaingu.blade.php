@extends('page::layout')

@php
    $pageProps = [
        "ngoaingu" => $ngoaingu,
    ];
@endphp

@section('content')
    <div id="root-store-ngoaingu" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Ngoaingu/viewStoreNgoaingu.tsx')
@endpush
