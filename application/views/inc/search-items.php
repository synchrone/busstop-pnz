<?php /** @var $items Model_Station[] */
foreach($items as $station)
{
    printf('<li>
        <a href="/forecast?id=%d&type=%s">
           <span class="ui-li-content">%s</span><span class="ui-li-desc zeromargin"> &rarr;%s</span>
        </a>
    </li>',
        $station->id,$station->type,
        $station->name,$station->heading);
}
?>