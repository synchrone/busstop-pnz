<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Fetch extends Minion_Task
{
    const BASE_URL = 'http://bus62.ru/penza/php/';
    public function _execute(array $params)
    {
        $cache = Cache::instance();

        if(!($routes = $cache->get('routes')))
        {
            $json_routes = Request::factory(self::BASE_URL.'searchAllRoutes.php')
                ->query('city','penza')
                ->execute()->body();

            $json_routes = json_decode($json_routes);
            $routes = array();
            foreach($json_routes as $type=>$csv)
            {
                $csv = explode('@ROUTE=',$csv);
                array_shift($csv);

                foreach($csv as $route)
                {
                    $route_info = array_combine(array(
                        'formal_name','full_name','name','start','finish','type','id1','id2'
                    ),str_getcsv($route,';'));
                    $routes[] = $route_info;
                }
            }
            $cache->set('routes',$routes);
        }

        if(!($stations = $cache->get('stations')))
        {
            $stations = array();

            foreach($routes as $route)
            {
                $xml_stations_body = Request::factory(
                    sprintf(self::BASE_URL.'getRouteStations.php')
                )
                ->query(array(
                    'city'=>'penza',
                    'type'=>0,
                    'id1'=>(int)$route['id1'],
                    'id2'=>(int)$route['id2']
                ))
                ->execute()->body();

                /** @var $xml_stations SimpleXMLElement */
                $xml_stations = simplexml_load_string($xml_stations_body);

                if($xml_stations === false){
                    Minion_CLI::write('Could not parse '. $xml_stations_body.' from '.var_export($route,true));
                    return;
                }

                foreach($xml_stations->children() as /** @var  $station_xml SimpleXMLElement */ $station_xml)
                {
                    $station_xml = (array)$station_xml;
                    $station_xml = $station_xml['@attributes'];
                    unset($station_xml['descr']);
                    unset($station_xml['lon1']);
                    unset($station_xml['lat1']);
                    unset($station_xml['end']);


                    $stations[$station_xml['id']] = $station_xml;
                }
            }
            $cache->set('stations',$stations,3600 * 24);
        }
        file_put_contents(DOCROOT.'www/js/stations.json',json_encode($stations));
    }
}