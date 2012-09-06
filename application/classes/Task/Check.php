<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Check extends Minion_Task
{
    const HUMAN = 'syn@li.ru';

    public function _execute(array $params){
        $stations = Cache::instance()->get('stations');
        $station = current($stations);

        try{
            Controller_Main::get_forecast_xml(
                array(
                    'id'=>$station['id'],
                    'type'=>$station['type'],
                    'city'=>Controller_Main::CITY
                )
            );
        }catch (Exception $e){
            $msg = Kohana_Exception::text($e).PHP_EOL.$e->getTraceAsString();
            mail(self::HUMAN,'WhenBus forecast fails!',$msg);
        }
    }
}