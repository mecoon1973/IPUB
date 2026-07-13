@extends('page::layout')
@php
    $pageProps = [
        "templateExport" => $TemplateExport,
    ];
@endphp

@section('content')
    <div id='root-store-template-export' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/system/view/TemplateExport/viewStoreTemplateExport.tsx')
@endpush
