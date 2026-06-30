@extends('page::layout')
@php
    $pageProps = [
        "sach" => $sach,
    ];
@endphp
@section('content')
    <div id='root-print-isbn' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/book/view/Sach/viewPrintISBN.tsx')
@endpush
