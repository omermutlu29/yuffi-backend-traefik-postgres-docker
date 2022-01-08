<script>
    setTimeout(() => {
        if (window.ReactNativeWebView) {
            let obj = {
                "type": "alert",
                "title": "Başarısız :/",
                "message": "Ödemenizi alamadık! Bankanızın döndüğü hata mesajı: {{$error}}",
                "buttons": [
                    {
                        "label": "Tekrar deneyelim",
                        "type": "redirect",
                        "route": "CompletePayment",
                    }
                ]
            };
            window.ReactNativeWebView.postMessage(JSON.stringify(obj));
        }
    }, 0);
</script>
