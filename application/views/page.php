<!DOCTYPE html>
<html>
<head>
	<title>When Bus ?</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Надоело стоять на остановке и не знать, когда будет транспорт? Дружественный к мобильным устройствам whenbus.ru подскажет!" />

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <!-- <link rel="apple-touch-startup-image" href="/img/startup.jpg">-->
    <link rel="apple-touch-icon" href="/img/iphone-icon.png" />

    <?php
    if(Kohana::$environment == Kohana::PRODUCTION){ ?>
        <link rel="stylesheet" href="/css/jquery.mobile-1.2.0-rc.2.min.css" />
        <link rel="stylesheet" href="/css/style.css" />
        <script src="/js/jquery-1.8.1.min.js"></script>
        <script src="/js/jquery.custom-settings.js"></script>
        <script src="/js/jquery.mobile-1.2.0-rc.2.min.js"></script>
        <script src="/js/jquery.cookie.js"></script>
        <script src="/js/jquery.total-storage.min.js"></script>
        <script src="/js/jquery.mobile.search.contrib.js"></script>
    <?php } else { ?>
        <link rel="stylesheet" href="/css/compiled.css" />
        <script src="/js/compiled.js"></script>
    <?php } ?>
</head>
<body>

<?=$content;?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function () {
        try {
            w.yandexMetrika = w.yaCounter16755400 = new Ya.Metrika({id:16755400, accurateTrackBounce:true, trackHash:true});
        } catch (e) {
        }
    });
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () {
            n.parentNode.insertBefore(s, n);
        };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f);
    } else {
        f();
    }
})(document, window, "yandex_metrika_callbacks");</script>
<noscript>
    <div><img src="//mc.yandex.ru/watch/16755400" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>