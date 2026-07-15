@extends('page::layout')

@php
    $pageProps = [
        "url" => $url,
        "id" => $id,
        "path_file_docx" => $path_file_docx,
    ];
@endphp

@section('content')
    <div id="root-print-phieu-dk-de-tai" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/topic/view/PhieuDkDetai/viewPrintPhieuDkDeTai.tsx')
@endpush
