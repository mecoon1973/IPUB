@extends('page::layout')
@php
    $pageProps = [
        Sach => $'.Sach.',
    ];
@endphp

@section('content')
    <div id='root-store-' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
    @vite('')
@endpush