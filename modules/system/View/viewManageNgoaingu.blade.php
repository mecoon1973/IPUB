@extends('page::layout')

@section('content')
    <div id="root-manage-ngoaingu"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Ngoaingu/viewManageNgoaingu.tsx')
@endpush
