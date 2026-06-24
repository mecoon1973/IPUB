@extends('page::layout')

@section('content')
    <div id='root-manage-ds-doc-ra-soat'></div>
@endsection

@push('scripts')
@vite('resources/ts/modules/qualityAssessment/view/DsDocRaSoat/viewManageDsDocRaSoat.tsx')
@endpush
