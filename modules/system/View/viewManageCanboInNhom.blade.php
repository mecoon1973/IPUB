@extends('page::layout')
@php
    $pageProps = [
        "nhom" => $nhom,
    ];
@endphp
@section('content')
    <div id="root-manage-canbo-in-nhom" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Nhom/viewManageCanboInNhom.tsx')
@endpush
