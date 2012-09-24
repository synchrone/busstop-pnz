<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Fetch extends Minion_Task
{
    protected  $_options = array(
        'cache'=>'true'
    );
    const BASE_URL = 'http://83.222.106.126/php/';
    public function _execute(array $params)
    {
        $cache = Cache::instance();

        if($params['cache'] !== 'true' || !($routes = $cache->get('routes')))
        {
            Minion_CLI::write('Fetching routes...');
            $routes = Model_Remote::routes();
            $cache->set('routes',$routes);
        }

        if($params['cache'] !== 'true' || !($stations = $cache->get('stations')))
        {
            $stations = array();

            foreach($routes as $route)
            {
                Minion_CLI::write('Fetching stations for route '.$route->formal_name.'ids:('.(int)$route->id1.','.$route->id2.')...');
                $xml_stations = Model_Remote::stations($route->id1,$route->id2);

                $headings = array(null);
                $current_heading = 0;

                foreach($xml_stations->children() as /** @var  $station_xml SimpleXMLElement */ $station_xml)
                {
                    $station_xml = (array)$station_xml;
                    $station_xml = $station_xml['@attributes'];
                    $station_xml['heading'] = &$headings[$current_heading];

                    if(Arr::get($station_xml,'end',0) == 1) //TODO: Стадион Пенза -> Стадион Пенза is just so wrong ...
                    {
                        $headings[$current_heading] = $station_xml['name']; //setting back-linked heading
                        $headings[] = null; //adding new empty heading
                        $current_heading = count($headings)-1;
                        Minion_CLI::write('Set heading for previous stops '.$station_xml['name']);
                    }
                    $stations[$station_xml['id']] = $station_xml;
                }
            }
            $stations = array_map(function($station){
                Minion_CLI::write($station['name'].' -> '.$station['heading']);
                Model_DidYouMean::instance()->learn($station['name']);
                return Model_Station::factory($station);
            },$stations);
            $lifetime = 3600 * 24 * 30;
            Model_DidYouMean::instance()->save($lifetime);

            $cache->set('stations',$stations,$lifetime);
        }
        file_put_contents(DOCROOT.'js/stations.json',json_encode($stations));
    }
}