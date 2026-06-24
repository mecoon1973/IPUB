@extends('page::layout')

@section('content')
    <div id="root-manage-mangsach"></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/Mangsach/viewManageMangsach.tsx')
@endpush
