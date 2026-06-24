@auth
    @php
        $authView = auth()->user();
        unset($authView->password);
    @endphp
    <script>
        // Bien global de frontend co the doc khi khoi tao store.
        window.__AUTH__ = @json($authView);
    </script>
@else
    <script>
        window.__AUTH__ = null;
    </script>
@endauth
