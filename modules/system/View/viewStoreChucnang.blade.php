@extends('page::layout')

@php
    $pageProps = [
        "chucnang" => $chucnang,
        "parentId" => $parentId,
        "listPhanhe" => $listPhanhe,
        ];
@endphp

@section('content')
    <div id="root-store-chucnang" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Chucnang/viewStoreChucnang.tsx')
@endpush
