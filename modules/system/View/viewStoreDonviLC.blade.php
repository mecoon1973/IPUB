@extends('page::layout')
@php
    $pageProps = [
        'DonviLC' => $DonviLC,
        'listLoaiXbpLc' => $listLoaiXbpLc,
    ];
@endphp

@section('content')
    <div id='root-store-donvilc' data-props='{{ json_encode($pageProps) }}'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/DonviLC/viewStoreDonviLC.tsx')
@endpush
