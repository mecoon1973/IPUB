@extends('page::layout')

@php
    $pageProps = [
        "mangsach" => $mangsach,
        "parentId" => $parentId,
    ];
@endphp

@section('content')
    <div id="root-store-mangsach" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Mangsach/viewStoreMangsach.tsx')
@endpush
