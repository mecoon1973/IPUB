@extends('page::layout')

@section('content')
    <div id='root-manage-to-khai-luu-chuyen'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/legalDeposit/view/ToKhaiLuuChuyen/viewManageToKhaiLuuChuyen.tsx')
@endpush
