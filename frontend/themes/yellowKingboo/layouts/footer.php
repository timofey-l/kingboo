<?php

use yii\helpers\Url;

?>

<footer class="main">
    <div class="olive-bottom-row"></div>
    <div class="left-ellipses"></div>
    <div class="footer-container">
        <div class="copyright">&copy; IT Design Studio 2015</div>
        <div class="footer-content"><a href="<?= Url::toRoute(['site/contact']) ?>" class="contact-link">Контактная информация</a></div>
        
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter32817837 = new Ya.Metrika({
                    id:32817837,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/32817837" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

    </div>
</footer>