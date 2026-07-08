@extends('page::layout')
@php
    $pageProps = [
        "templateExcel" => $TemplateExcel,
    ];
@endphp

@section('content')
    <div id='root-store-template-excel' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/system/view/TemplateExcel/viewStoreTemplateExcel.tsx')
@endpush
