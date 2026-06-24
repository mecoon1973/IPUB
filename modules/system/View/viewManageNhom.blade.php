@extends('page::layout')

@section('content')
    <div id="root-manage-nhom"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Nhom/viewManageNhom.tsx')
@endpush
