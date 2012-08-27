<!DOCTYPE html>
<html>
<head>
	<title>When Bus ?</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Надоело стоять на остановке и не знать, когда будет транспорт? Дружественный к мобильным устройствам whenbus.ru подскажет!" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0-alpha.1/jquery.mobile-1.2.0-alpha.1.min.css" />
    <link rel="stylesheet" href="/css/style.css" />
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.0-alpha.1/jquery.mobile-1.2.0-alpha.1.min.js"></script>

</head>
<body>

<div id="main_page" data-role="page">

	<div data-role="header" data-theme="b">
		<h1>When Bus ?</h1>
        <a href="#about" data-role="button" data-icon="info"
           class="ui-btn-right info-button" data-rel="dialog">Инфо</a>
	</div>

	<div data-role="content">
        <input type="search" id="search-station" value="" data-timeout="500" placeholder="Введите название остановки"/>

        <ul id="bus_stops" data-role='listview' data-inset='true'>

        </ul>
	</div>
</div>

<div id="forecast" data-role="page">
    <div data-role="header"  data-theme="b">
        <a href="#" data-rel="back" data-icon="arrow-l" data-iconpos="right">Назад</a>
        <h1></h1>
        <a href="#" class="refresh" data-role="button" data-icon="refresh">Обновить</a>
    </div>
    <div data-role="content"></div>
</div>

<div id="about" data-role="page">
    <div data-role="header">
        <h1>Инфо</h1>
    </div>
    <div data-role="content">
        <p>Просто введи название остановки, где дожидаешься транспорта и увидишь, когда он приедет</p>
        <div data-role="collapsible" class="geo-debug" data-collapsed="true">
            <h3>Твои координаты</h3>
            <p>Данные недоступны</p>
        </div>
    </div>
</div>


<script src='/js/main.js'></script>
</body>
</html>