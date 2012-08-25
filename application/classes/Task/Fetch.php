<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Fetch extends Minion_Task
{
    protected  $_options = array(
        'cache'=>'true'
    );
    const BASE_URL = 'http://bus62.ru/penza/php/';
    public function _execute(array $params)
    {
        $cache = Cache::instance();

        if($params['cache'] !== 'true' || !($routes = $cache->get('routes')))
        {
            Minion_CLI::write('Fetching routes...');
            /** @var $json_routes Request_Client */
            $json_routes = Request::factory(self::BASE_URL.'searchAllRoutes.php')
                ->query('city','penza')
                ->execute()
            ->body();

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

        if($params['cache'] !== 'true' || !($stations = $cache->get('stations')))
        {
            $stations = array();

            foreach($routes as $route)
            {
                Minion_CLI::write('Fetching stations for route '.$route['formal_name'].'...');
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

                $headings = array(null);
                $current_heading = 0;
                $i = 0;

                foreach($xml_stations->children() as /** @var  $station_xml SimpleXMLElement */ $station_xml)
                {
                    $station_xml = (array)$station_xml;
                    $station_xml = $station_xml['@attributes'];
                    $station_xml['heading'] = &$headings[$current_heading];

                    if(Arr::get($station_xml,'end',0) == 1)
                    {
                        $headings[$current_heading] = $station_xml['name']; //setting back-linked heading
                        $headings[] = null; //adding new empty heading
                        $current_heading = count($headings)-1;
                        Minion_CLI::write('Set heading for previous stops '.$station_xml['name']);
                    }
                    $stations[$station_xml['id']] = $station_xml;
                }
                $headings = array(null);
                $current_heading = 0;
                $i = 0;
            }
            $cache->set('stations',$stations,3600 * 24);
        }
        //TODO: use MongoDB for storing stations
        file_put_contents(DOCROOT.'js/stations.json',json_encode($stations));
    }
}