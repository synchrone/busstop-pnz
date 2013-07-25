<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property int $obj_id
 * @property int $station_id
 * @property int $lon0
 * @property int $lat0
 * @property int $lon1
 * @property int $lat1
 *
 */
class Model_Forecast_Station extends Model_Forecast
{
    public function get_station(){
        return Model_Station::by_id($this->station_id, true);
    }
}