@extends('page::layout')

@section('content')
    <div id="root-manage-chucvu"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Chucvu/viewManageChucvu.tsx')
@endpush
