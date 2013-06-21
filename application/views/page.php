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
    <link rel="icon" href="/img/icon-57x57.png" />
    <link rel="shortcut icon" href="/img/icon-57x57.png" />
    <link rel="apple-touch-icon" href="/img/icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon-144x144.png" />

    <!-- Yandex.Metrika counter is here to set w.yandexMetrika before compiled.js is executed -->
    <script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function () {
            try {
                w.yandexMetrika = w.yaCounter16755400 = new Ya.Metrika({id: <?=$metrika_id?>, accurateTrackBounce:true, trackHash:true});
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

    <?php
    if(Kohana::$environment == Kohana::DEVELOPMENT){ ?>
        <link rel="stylesheet" href="/css/jquery.mobile-1.2.0.min.css" />
        <link rel="stylesheet" href="/css/style.css" />
        <script src="/js/jquery-1.8.1.min.js"></script>
        <script src="/js/jquery.custom-settings.js"></script>
        <script src="/js/jquery.mobile-1.2.0.min.js"></script>
        <script src="/js/jquery.cookie.js"></script>
        <script src="/js/jquery.total-storage.min.js"></script>
        <script src="/js/jquery.mobile.search.contrib.js"></script>
        <script src="/js/jquery.timer.js"></script>
        <script src="/js/main.js"></script>
    <?php } else { ?>
        <link rel="stylesheet" href="/css/compiled.css" />
        <script src="/js/compiled.js"></script>
    <?php } ?>
</head>
<body>

<?=$content;?>

</body>
</html>