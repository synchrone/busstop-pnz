<?php defined('SYSPATH') or die('No direct script access.');

class Task_Popstations extends Minion_Task
{
    protected $_options = array();

    protected static function get_token(){
        if(!($token = Arr::get($_SERVER,'METRIKA_TOKEN')))
        {
            $token = Minion_CLI::read('Input metrika auth token from '.
                'https://oauth.yandex.ru/authorize?response_type=token&client_id='.Kohana::$config->load('metrika.client_id')
            );
        }
        return $token;
    }

    protected function _execute(array $options)
    {
        $stations = Model_Remote::popular_stations(array('oauth_token' => self::get_token()));
        $stations = array_slice($stations,0,7); //can't limit, so we cut off

        Cache::instance()->set('popular_stations', $stations, Task_Fetch::CACHE_LIFETIME);
    }
}