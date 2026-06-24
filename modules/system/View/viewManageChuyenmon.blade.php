@extends('page::layout')

@section('content')
    <div id="root-manage-chuyenmon"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Chuyenmon/viewManageChuyenmon.tsx')
@endpush
