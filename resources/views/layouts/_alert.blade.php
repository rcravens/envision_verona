@if (session()->has('sweet_alert'))
    <script defer>
        window.addEventListener('load', function () {
            App.alert({!! session('sweet_alert') !!});
        });
    </script>
@endif
