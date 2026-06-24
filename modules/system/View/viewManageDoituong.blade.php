
@extends('page::layout')

@section('content')
    <div id="root-manage-doituong"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Doituong/viewManageDoituong.tsx')
@endpush
