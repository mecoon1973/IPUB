@extends('page::layout')

@section('content')
    <div id="root-manage-donvi"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Donvi/viewManageDonvi.tsx')
@endpush
