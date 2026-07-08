@extends('page::layout')

@php
    $pageProps = [
        'listUsers' => $listUsers,
    ];
@endphp

@section('content')
    <div id="root-manage-phieu-dk-khxb-cxb" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkKhxbCxb/viewManagePhieuDkKhxbCxb.tsx')
@endpush
