@extends('page::layout')

@php
    $pageProps = [
        "nhom" => $nhom,
        "listQuyen" => $listQuyen,
        ];
@endphp

@section('content')
    <div id="root-permission-settings-nhom" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Nhom/viewPermissionSettingsNhom.tsx')
@endpush
