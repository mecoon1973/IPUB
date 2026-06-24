@extends('page::layout')

@php
    $pageProps = [
        "quyen" => $quyen,
        "listChucnang" => $listChucnang
        ];
@endphp

@section('content')
    <div id="root-permission-settings" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Quyen/viewPermissionSettings.tsx')
@endpush
