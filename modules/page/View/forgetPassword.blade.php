@extends('page::layout', ['usingHeader' => false])
@viteReactRefresh
@vite('resources/ts/modules/page/view/Login/viewForgetPassword.tsx')
@section('content')
    <div id="root-form-forget-password"></div>
@endsection

