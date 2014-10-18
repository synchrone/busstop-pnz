<?php
    /** @var $forecast Model_Forecast_Station[]
     * @var $title string
     */
?><div id="forecast" data-role="page">
    <div data-role="header"  data-theme="b">
        <a href="#" data-rel="back" data-icon="arrow-l">Назад</a>
        <h1>Прогноз прибытия <?=HTML::chars($title)?></h1>
    </div>
    <div data-role="content">
        <ul class="forecast" data-role="listview" data-inset="true">
            <?php
                foreach($forecast as $item)
                {
                    $station = $item->get_station();
                    printf("<li>
                                <!-- img src='img/busstop.png' alt='%s' class='ui-li-icon'-->
                                <span class='ui-li-station'>%s</span>
                                <span class='ui-li-count'>%s</span>
                            </li>",
                        $station->name, $station->name, $item->arrive_time()
                    );
                }
            ?>
        </ul>
    </div>

    <fieldset class="ui-grid-a">
        <div class="ui-block-a">&nbsp;</div>
        <div class="ui-block-b">
            <button class="refresh" data-mini="true" data-icon="refresh" data-iconpos="right">Обновление <span class="refresh_in">9</span>c.</button>
        </div>
    </fieldset>
</div>