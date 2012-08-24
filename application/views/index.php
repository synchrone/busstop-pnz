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
		<h1>Остановка</h1>
	</div>

	<div data-role="content">
        <input type="search" id="search-station" onchange="searchStop(this.value)" value=""/>

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

<script src='/js/main.js'></script>
</body>
</html>