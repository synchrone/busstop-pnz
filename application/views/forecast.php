<?php
    /** @var $forecast Model_Forecast[] */
    /** @var $station Model_Station */
?><div id="forecast" data-role="page" data-station-id="<?=$station->id?>">
    <div data-role="header"  data-theme="b">
        <a href="#" data-rel="back" data-icon="arrow-l">Назад</a>
        <h1 class="station_name"><?=$station->name?></h1>
    </div>
    <div data-role="content">
        <ul class="forecast" data-role="listview" data-inset="true">
            <?=View::factory('inc/forecast-items')->set('items',$forecast)->render()?>
        </ul>
    </div>

    <fieldset class="ui-grid-a">
        <div class="ui-block-a">
            <button class="favorite" data-mini="true" data-icon="minus" data-iconpos="left"></button>
        </div>
        <div class="ui-block-b">
            <button class="refresh" data-mini="true" data-icon="refresh" data-iconpos="right">Обновление <span class="refresh_in">9</span>c.</button>
        </div>
    </fieldset>
</div>