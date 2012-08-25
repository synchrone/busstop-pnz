<!DOCTYPE html>
<html>
<head>
	<title>BusStop</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0-alpha.1/jquery.mobile-1.2.0-alpha.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.0-alpha.1/jquery.mobile-1.2.0-alpha.1.min.js"></script>

</head>
<body>

<div id="main_page" data-role="page">

	<div data-role="header">
		<h1>Остановки</h1>
        <a href="#about" data-role="button" data-icon="info"
           data-theme="b" class="ui-btn-right info-button" data-rel="dialog">info</a>
	</div>

	<div data-role="content">
        <input type="search" id="search-station" value=""/>

        <ul id="bus_stops" data-role='listview' data-inset='true'>

        </ul>
	</div>
</div>

<div id="forecast" data-role="page">
    <div data-role="header">
        <a href="#" data-rel="back" data-icon="arrow-l" data-iconpos="right">Назад</a>
        <h1></h1>
        <a href="#" class="refresh" data-role="button" data-icon="refresh">Обновить</a>
    </div>
    <div data-role="content"></div>
</div>

<div id="about" data-role="page">
    <div data-role="header">
        <h1>Info</h1>
    </div>
    <div data-role="content">
        <div data-role="collapsible" class="geo-debug" data-collapsed="true">
            <h3>Geo-info</h3>
            <p>No Geo-info available</p>
        </div>
    </div>
</div>


<script src='/js/main.js'></script>
</body>
</html>