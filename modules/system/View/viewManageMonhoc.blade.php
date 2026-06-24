@extends('page::layout')

@section('content')
    <div id="root-manage-monhoc"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Monhoc/viewManageMonhoc.tsx')
@endpush
