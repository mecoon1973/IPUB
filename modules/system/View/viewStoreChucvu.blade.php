@extends('page::layout')

@php
    $pageProps = [
        "chucvu" => $chucvu,
    ];
@endphp

@section('content')
    <div id="root-store-chucvu" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Chucvu/viewStoreChucvu.tsx')
@endpush
