@extends('page::layout')

@section('content')
    <div id='root-manage-donvilc'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/system/view/DonviLC/viewManageDonviLC.tsx')
@endpush
