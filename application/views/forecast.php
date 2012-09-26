<?php
    /** @var $forecast Model_Forecast[] */
    /** @var $station Model_Station */
?><div id="forecast" data-role="page" data-station-id="<?=$station->id?>">
    <div data-role="header"  data-theme="b">
        <a href="#" data-rel="back" data-icon="arrow-l">Назад</a>
        <h1><?=$station->name?></h1>
        <a href="#" class="refresh" data-role="refresh" data-icon="refresh">Обновить</a>
    </div>
    <div data-role="content">
        <ul class="forecast" data-role="listview" data-inset="true">
            <?=View::factory('inc/forecast-items')->set('items',$forecast)->render()?>
        </ul>
    </div>

    <fieldset class="ui-grid-solo"><div class="ui-block-a">
        <button class="favorite" data-icon="star" data-iconpos="left"></button>
    </div></fieldset>
</div>