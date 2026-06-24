@extends('page::layout')

@section('content')
    <div id="root-manage-loai-xbp-lc"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/LoaiXBPLC/viewManageLoaiXbpLc.tsx')
@endpush
