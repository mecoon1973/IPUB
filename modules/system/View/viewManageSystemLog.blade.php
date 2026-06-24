@extends('page::layout')
@php
    $pageProps = [
        "listDonvi" => $listDonvi,
    ];
@endphp
@section('content')
    <div id="root-manage-system-log" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/SystemLog/viewManageSystemLog.tsx')
@endpush
