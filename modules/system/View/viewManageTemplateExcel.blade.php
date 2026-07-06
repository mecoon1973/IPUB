@extends('page::layout')

@section('content')
    <div id='root-manage-template-excel'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/TemplateExcel/viewManageTemplateExcel.tsx')
@endpush
