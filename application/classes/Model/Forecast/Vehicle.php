<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property string where
 * @property string vehid
 * @property int rid
 * @property string rtype
 * @property string rnum
 * @property string lastst
 */
class Model_Forecast_Vehicle extends Model_Forecast
{
    public function get_route(){
        return Model_Route::by_id($this->rid);
    }

    public function get_shortname(){
        return $this->rtype.'-'.$this->rnum;
    }
}