@extends('page::layout')

@php
    $pageProps = [
        'listDonvi' => $listDonvi ?? [],
    ];
@endphp

@section('content')
    <div id="root-manage-phieu-chuyen-ban-thao" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuChuyenBanThao/viewManagePhieuChuyenBanThao.tsx')
@endpush
