<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property string route_type
 * @property string route_num
 * @property string where_go
 * @property int arr_time
 * @property int obj_id
 */
class Model_Forecast extends Model
{
    public function arrive_time(){
        return Text::minutes($this->arr_time);
    }
}