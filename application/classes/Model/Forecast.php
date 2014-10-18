<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property $arrt Время прибытия
 */
class Model_Forecast extends Model
{
    public function arrive_time(){
        return Text::minutes($this->arrt);
    }
}