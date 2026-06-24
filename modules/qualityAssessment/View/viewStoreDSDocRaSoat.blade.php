@extends('page::layout')
@php
    $pageProps = [
        "DSDocRaSoat" => $DSDocRaSoat,
        "listDonvi" => $listDonvi,
        "listMangsach" => $listMangsach,
    ];
@endphp

@section('content')
    <div id='root-store-ds-doc-ra-soat' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/qualityAssessment/view/DsDocRaSoat/viewStoreDsDocRaSoat.tsx')
@endpush
