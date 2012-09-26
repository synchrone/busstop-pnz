<?php /** @var $items Model_Station[] */
foreach($items as $station)
{
    printf('<li><a href="/forecast?id=%d&type=%s">%s (&rarr;%s)</a></li>',
        $station->id,$station->type,
        $station->name,$station->heading);
}
?>