@extends('page::layout')

@section('content')
    <div id='root-manage-template-export'></div>
@endsection

@push('scripts')
    @vite('resources/ts/modules/system/view/TemplateExport/viewManageTemplateExport.tsx')
@endpush
