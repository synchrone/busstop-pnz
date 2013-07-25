<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property $arr_time Время прибытия
 */
class Model_Forecast extends Model
{
    public function arrive_time(){
        return Text::minutes($this->arr_time);
    }
}