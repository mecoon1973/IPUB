
@extends('page::layout')

@section('content')
    <div id="root-manage-chucnang"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Chucnang/viewManageChucnang.tsx')
@endpush
