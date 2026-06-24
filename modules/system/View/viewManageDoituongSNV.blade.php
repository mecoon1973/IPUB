@extends('page::layout')

@section('content')
    <div id='root-manage-doituong-snv'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/system/view/DoituongSNV/viewManageDoituongSNV.tsx')
@endpush
