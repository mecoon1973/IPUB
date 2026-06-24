@extends('page::layout')

@php
    $pageProps = [
        "quyen" => $quyen,
        "parentId" => $parentId,
    ];
@endphp

@section('content')
    <div id="root-store-quyen" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Quyen/viewStoreQuyen.tsx')
@endpush
