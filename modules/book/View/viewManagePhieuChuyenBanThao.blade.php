@extends('page::layout')

@section('content')
    <div id='root-manage-phieu-chuyen-ban-thao'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/book/view/PhieuChuyenBanThao/viewManagePhieuChuyenBanThao.tsx')
@endpush
