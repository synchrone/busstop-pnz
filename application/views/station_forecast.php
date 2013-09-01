<?php
/**
 * @var $forecast Model_Forecast_Vehicle[]
 * @var $station Model_Station
 * @var $vehicles_enroute int Enroute vehicle count
 */
?><div id="forecast" data-role="page" data-station-id="<?=$station->id?>">
    <div data-role="header"  data-theme="b">
        <a href="#" data-rel="back" data-icon="arrow-l">Назад</a>
        <h1 class="station_name"><?=$station->name?></h1>
    </div>
    <div data-role="content">
        <ul class="forecast" data-role="listview" data-inset="true">
            <?
                foreach($forecast as $item)
                {
                    printf('<li>
                                <a href="/vehicle_forecast?id=%s&type=%d&title=%s">
                                    <img src="img/%s.png" alt="%s" class="ui-li-icon">
                                    <span class="ui-li-route">%s</span>
                                    &rarr;%s<span class="ui-li-count">%s</span>
                                </a>
                            </li>',
                        $item->obj_id, $station->type, $item->route_num,
                        $item->route_type, $item->route_num, $item->route_num, $item->where_go,
                        $item->arrive_time()
                    );
                }

                if($vehicles_enroute === 0){ ?>
                <div class="ui-body ui-body-e">
                    Нет проходящего транспорта на маршрутах. <!--a href="tel:000000">Позвонить в такси ?</a-->
                </div>
            <?  }
            ?>
        </ul>
    </div>

    <fieldset class="ui-grid-a">
        <div class="ui-block-a">
            <button class="favorite" data-mini="true" data-iconpos="left"></button>
        </div>
        <div class="ui-block-b">
            <button class="refresh" data-mini="true" data-icon="refresh" data-iconpos="right">Обновление <span class="refresh_in">9</span>c.</button>
        </div>
    </fieldset>
</div>