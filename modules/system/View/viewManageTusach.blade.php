@extends('page::layout')

@section('content')
    <div id="root-manage-tusach"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Tusach/viewManageTusach.tsx')
@endpush
