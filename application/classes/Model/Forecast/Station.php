<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property int stid: 24,
 * @property string stname: "Депо-1",
 * @property string stdescr: "из центра",
 * @property int lat0: 53201823,
 * @property int lng0: 45001717,
 * @property int lat1: 53201900,
 * @property int lng1: 45000901
 *
 */
class Model_Forecast_Station extends Model_Forecast
{
    public function get_station(){
        return Model_Station::by_id($this->stid, true);
    }
}