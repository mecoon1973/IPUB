@extends('page::layout')

@php
    $pageProps = [
        "donvi" => $donvi,
        "parentId" => $parentId,
    ];
@endphp

@section('content')
    <div id="root-store-donvi" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Donvi/viewStoreDonvi.tsx')
@endpush
