@extends('page::layout')

@section('content')
    <div id="root-manage-user"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/user/view/User/viewManageUser.tsx')
@endpush
