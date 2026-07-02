@extends('page::layout')

@php
    $pageProps = [
        'listDonvi' => $listDonvi,
        'listMangsach' => $listMangsach,
        'mapTrangThai' => $mapTrangThai,
    ];
@endphp

@section('content')
    <div id="root-manage-hdxb-nxbgdvn" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/HDXBNXBGDVN/viewManageHDXBNXBGDVN.tsx')
@endpush
