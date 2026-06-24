@extends('page::layout')

@php
    $pageProps = ["bosach" => $bosach];
@endphp

@section('content')
    <div id="root-store-bosach" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Bosach/viewStoreBosach.tsx')
@endpush
