@extends('page::layout', ['usingHeader' => false])
@viteReactRefresh
@vite('resources/ts/modules/page/view/Login/viewLogin.tsx')
@section('content')
    <div id="root-form-login"></div>
@endsection

