@extends('page::layout')

@section('content')
    <div id="root-manage-lop"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Lop/viewManageLop.tsx')
@endpush
