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
        $cache->set('routes',$routes,self::CACHE_LIFETIME);

        $stations = array();
        $routes_thru_stations = array();

        foreach($routes as $route)
        {
            Minion_CLI::write(sprintf('Fetching stations for route %s (%d,%d)...',$route->formal_name, $route->id1, $route->id2));

            $stations_there = Model_Remote::stations($route->id1,$route->finish);
            $stations_backhere = Model_Remote::stations($route->id2,$route->start);

            foreach($stations_there + $stations_backhere as $station){
                if(!isset($routes_thru_stations[$station->id])){
                    $routes_thru_stations[$station->id] = array();
                }
                $routes_thru_stations[$station->id][] = $route->id1;
            }

            $stations = $stations_there + $stations_backhere + $stations;
            //TODO: merging like this makes station with different routes going to the opposite sides of the city be ambigous
            //proposed solution -> iterate over found stations for each route and make heading list in each station
        }

        $cache->set('routes_thru_stations', $routes_thru_stations, self::CACHE_LIFETIME);
        unset($routes_thru_stations);

        Model_DidYouMean::instance()->clear();
        array_map(function($station){
            Model_DidYouMean::instance()->learn($station->name);
            return $station;
        },$stations);

        Model_DidYouMean::instance()->save(self::CACHE_LIFETIME);
        $cache->set('stations', $stations, self::CACHE_LIFETIME);
    }
}