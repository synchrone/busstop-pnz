<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property int $id
 * @property string $gos_num
 * @property int $lon
 * @property int $lat
 * @property int $dir
 * @property string $anim_points
 * @property int $rnum Route number (human)
 * @property string $rtype Route type (1-letter)
 * @property int $rid Route id
 * @property string $lasttime
 */
class Model_Vehicle extends Model_Geo
{
    public function get_route()
    {
        return Model_Route::by_id($this->rid);
    }

    public function lat(){
        return $this->floatify($this->lat);
    }
    public function lon(){
        return $this->floatify($this->lon);
    }

    public function lasttime(){
        return (new DateTime($this->lasttime));
    }
}