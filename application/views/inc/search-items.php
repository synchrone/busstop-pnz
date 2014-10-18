<?php /** @var $items Model_Station[] */
foreach($items as $station)
{
    printf(
        '<li>
            <a href="/station_forecast?sid=%d&type=%s">
               <span class="ui-li-content">%s</span>
               <div>
                   <span class="ui-li-desc zeromargin">%s</span>
               </div>
            </a>
        </li>',
        $station->id,$station->type,
        $station->name,
        $station->descr
    );
}
?>