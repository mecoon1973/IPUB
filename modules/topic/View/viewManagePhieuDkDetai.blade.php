@extends('page::layout')

@php
    $pageProps = [
        'listDonvi' => $listDonvi,
        'mapTrangThai' => $mapTrangThai,
        'listMangsach' => $listMangsach,
        'listDoituong' => $listDoituong,
    ];
@endphp

@section('content')
    <div id="root-manage-phieu-dk-detai" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkDetai/viewManagePhieuDkDetai.tsx')
@endpush
