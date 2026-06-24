@extends('page::layout')

@php
    $pageProps = [
        "lop" => $lop,
    ];
@endphp

@section('content')
    <div id="root-store-lop" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Lop/viewStoreLop.tsx')
@endpush
