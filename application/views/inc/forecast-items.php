<?php /** @var $items Model_Forecast[] */

foreach($items as $item)
{
    if(strlen($item->route_num)<=2){
        $route_num=$item->route_num.'&nbsp;&nbsp;';
    }else{
        $route_num=$item->route_num;
    }
    printf("<li><img src='img/%s.png' alt='%s' class='ui-li-icon'>%s &rarr;%s
            <span class='ui-li-count'>%s</span></li>",
        $item->route_type, $item->route_num, $route_num, $item->where_go,
        $item->arrive_time()
    );
}
?>