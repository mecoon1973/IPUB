@extends('page::layout')

@php
    $pageProps = [
        'listDonvi' => $listDonvi,
    ];
@endphp

@section('content')
    <div id="root-phe-duyet-di-in-hdxb-nxbgdvn" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/HDXBNXBGDVN/viewPheDuyetDiInHDXBNXBGDVN.tsx')
@endpush
