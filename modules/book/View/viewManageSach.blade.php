@extends('page::layout')
@php
    $pageProps = [
        "listDonvi" => $listDonvi,
        "listMangsach" => $listMangsach,
    ];
@endphp
@section('content')
    <div id='root-manage-sach' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/book/view/Sach/viewManageSach.tsx')
@endpush
