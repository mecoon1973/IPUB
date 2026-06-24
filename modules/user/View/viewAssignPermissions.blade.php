@extends('page::layout')

@php
    $pageProps = [
        "user" => $user,
        "listNhom" => $listNhom,
        "listQuyen" => $listQuyen,
        ];
@endphp

@section('content')
    <div id="root-assign-permissions" data-props="{{ json_encode($pageProps) }}"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/user/view/User/viewAssignPermissions.tsx')
@endpush
