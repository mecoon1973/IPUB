@extends('page::layout')

@section('content')
    <div id="root-manage-bosach"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Bosach/viewManageBosach.tsx')
@endpush
