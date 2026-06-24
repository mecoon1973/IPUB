@extends('page::layout')

@php
    $pageProps = ["user" => $user];
@endphp

@section('content')
    <div id="root-create-account" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/user/view/User/viewCreateAccount.tsx')
@endpush
