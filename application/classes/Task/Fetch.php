<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Fetch extends Minion_Task
{
    const CACHE_LIFETIME = 2592000;

    protected  $_options = array(
        'cache'=>'true'
    );

    public function _execute(array $params)
    {
        $cache = Cache::instance();
        Minion_CLI::write('Fetching stations...');

        $stations = Model_Remote::stations();
        $cache->set('stations', $stations, self::CACHE_LIFETIME);
        Minion_CLI::write(sprintf('done fetching %d stations', count($stations)));

        Model_DidYouMean::instance()->clear();
        array_map(function(Model_Station $station){
            Model_DidYouMean::instance()->learn($station->name);
            return $station;
        },$stations);
        Model_DidYouMean::instance()->save(self::CACHE_LIFETIME);
    }
}