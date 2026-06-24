@include('page::render-variable-js')

@vite('resources/ts/modules/core/core.ts')


<!-- private-js -->
@stack('private-js')
<!-- end private-js -->

@stack("custom-js")


@stack('scripts')

