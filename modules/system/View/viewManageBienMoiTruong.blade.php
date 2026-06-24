@extends('page::layout')

@section('content')
    <div id="root-manage-bien-moi-truong"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/BienMoiTruong/viewManageBienMoiTruong.tsx')
@endpush
