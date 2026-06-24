<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite('resources/ts/modules/core/core.ts')
    @viteReactRefresh
    @vite('resources/ts/modules/page/component/header/Header.tsx')
</head>
<body>
<div id="root-header"></div>

{{-- React component: bundle từ node_modules (chạy npm run build) --}}
<div id="react-root" data-title="React component trong Blade"></div>
</body>

</html>
