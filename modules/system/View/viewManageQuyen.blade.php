@extends('page::layout')

@section('content')
    <div id="root-manage-quyen"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Quyen/viewManageQuyen.tsx')
@endpush
