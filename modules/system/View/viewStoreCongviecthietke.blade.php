@extends('page::layout')

@php
    $pageProps = ["congviecthietke" => $congviecthietke];
@endphp

@section('content')
    <div id="root-store-congviecthietke" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Congviecthietke/viewStoreCongviecthietke.tsx')
@endpush
