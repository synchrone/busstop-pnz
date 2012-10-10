<?php /** @var $items Model_Forecast[] */
foreach($items as $item)
{
    printf('<li><img src="img/%s.png" alt="%s" class="ui-li-icon">%d &rarr;%s
            <span class="ui-li-count">%s</span></li>',
        $item->route_type, $item->route_num, $item->route_num, $item->where_go,
        $item->arrive_time()
    );
}
?>