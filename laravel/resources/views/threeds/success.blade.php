<script>
    setTimeout(() => {
        if (window.ReactNativeWebView) {
            let obj = {
                "type": "redirect",
                "route": "AccountVerification",
            };
            window.ReactNativeWebView.postMessage(JSON.stringify(obj));
        }
    }, 0);
</script>
