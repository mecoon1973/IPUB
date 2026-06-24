@extends('page::layout')

@section('content')
    <div id="root-manage-loai-xbp"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/LoaiXBP/viewManageLoaiXBP.tsx')
@endpush
