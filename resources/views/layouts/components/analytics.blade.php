<script async src="https://www.googletagmanager.com/gtag/js?id=UA-168004421-1"></script>
<script nonce="{{ csp_nonce() }}">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ config('analytics.id') }}');
</script>
