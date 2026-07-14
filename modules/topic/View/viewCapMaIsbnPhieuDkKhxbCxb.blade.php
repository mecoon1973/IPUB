@extends('page::layout')

@php
    $pageProps = [
        'listUsers' => $listUsers,
        'phieuDkKhxbCxb' => $phieuDkKhxbCxb,
        'listDeTai' => $listDeTai,
    ];
@endphp

@section('content')
    <div id="root-cap-ma-isbn-phieu-dk-khxb-cxb" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkKhxbCxb/viewCapMaIsbnPhieuDkKhxbCxb.tsx')
@endpush
