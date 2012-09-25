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

        Minion_CLI::write('Fetching routes...');
        $routes = Model_Remote::routes();
        $stations = array();

        foreach($routes as $route)
        {
            Minion_CLI::write(sprintf('Fetching stations for route %s (%d,%d)...',$route->formal_name, $route->id1, $route->id2));

            $stations_there = Model_Remote::stations($route->id1,$route->finish);
            $stations_backhere = Model_Remote::stations($route->id2,$route->start);
            $stations = array_merge($stations,$stations_there,$stations_backhere);
            //TODO: merging like this makes station with different routes going to the opposite sides of the city be ambigous
            //proposed solution -> iterate over found stations for each route and make heading list in each station
        }

        Model_DidYouMean::instance()->clear();
        array_map(function($station){
            Minion_CLI::write(sprintf('%d:%s -> %s',$station->id,$station->name,$station->heading));
            Model_DidYouMean::instance()->learn($station->name);
            return $station;
        },$stations);

        Model_DidYouMean::instance()->save(self::CACHE_LIFETIME);
        $cache->set('stations',$stations,self::CACHE_LIFETIME);
    }
}