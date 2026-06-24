@extends('page::layout')

@php
    $pageProps = [
        'listDonvi' => $listDonvi,
        'listMangsach' => $listMangsach,
    ];
@endphp

@section('content')
    <div id="root-chuyen-ke-hoach-phieu-dk-detai" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkDetai/viewChuyenKeHoachPhieuDkDetai.tsx')
@endpush
