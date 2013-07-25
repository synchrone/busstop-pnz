<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Model_Remote extends Model
{
    const CITY = 'penza';
    const BASE_URL = 'http://83.222.106.126/php/';

    protected static function request($url,$query_params = array())
    {
        if(strpos($url,'://') === false){
            $url = self::BASE_URL.$url;
        }

        /** @var $response Response */
        $response = Request::factory($url)
            ->headers('Cache-Control','no-cache')
            ->headers('Pragma','no-cache')
            ->query(array('city' => self::CITY) + $query_params)
            ->execute();

        if($response->status() !== 200){
            throw HTTP_Exception::factory($response->status(),$response->body());
        }

        return $response->body();

    }
    protected static function json_request($url,$query_params = array())
    {
        $json = self::request($url,$query_params);
        $data = json_decode($json);
        if($data === null)
        {
            throw new Kohana_Exception('Cannot parse ":json" from :url',
                array(':json'=>$json, ':url' => $url.'?'.http_build_query($query_params)));
        }
        return $data;
    }
    protected static function xml_request($url,$query_params = array())
    {
        $xml = self::request($url,$query_params);

        /** @var $data SimpleXMLElement */
        $data = simplexml_load_string($xml);
        if($data === false)
        {
            throw new Kohana_Exception('Could not parse ":xml" from :url',
                array(':xml'=>$xml, ':url' => $url.'?'.http_build_query($query_params)));
        }
        return $data;
    }

    /**
     * @return Model_Route[]
     */
    public static function routes()
    {
        $data = self::json_request('searchAllRoutes.php');
        $routes = array();
        foreach($data as $type=>$csv)
        {
            $csv = explode('@ROUTE=',$csv);
            array_shift($csv);

            foreach($csv as $route)
            {
                $route_info = array_combine(array(
                    'formal_name','full_name','name','start','finish','type','id1','id2'
                ),str_getcsv($route,';'));
                $routes[] = Model_Route::factory($route_info);
            }
        }
        return $routes;
    }

    /**
     * @param $route_id
     * @param $heading
     * @return Model_Station[]
     */
    public static function stations($route_id,$heading)
    {
        $stations = self::xml_request('getRouteStations.php',array(
            'type'=>0,
            'id1'=>(int)$route_id,
            'id2'=>(int)$route_id
        ));

        $data = array();
        foreach($stations->children() as $station){
            $station = (array)$station;
            /** @var $station Model_Station */
            $station = Model_Station::factory($station['@attributes']);
            $station->heading = $heading;
            $data[(string)$station->id] = $station;
            unset($station);
        }
        unset($stations);
        array_pop($data); //removing last stop, because this is the first one of the opposite direction
        return $data;
    }

    public static function station_forecast($query = array()){
        $xml_vehicles = self::xml_request('getStationForecasts.php',$query);
        $forecast = array();
        foreach($xml_vehicles->children() as $vehicle){
            $vehicle= (array)$vehicle;
            $forecast[] = Model_Forecast_Vehicle::factory($vehicle['@attributes']);
        }
        return $forecast;
    }

    public static function vehicle_forecast($query = array()){
        $xml_stations = self::xml_request('getVehicleForecasts.php',$query);
        $forecast = array();
        foreach($xml_stations->children() as $stations_forecast){
            $stations_forecast = (array)$stations_forecast;
            $forecast[] = Model_Forecast_Station::factory($stations_forecast['@attributes']);
        }
        return $forecast;
    }

    public static function popular_stations($query = array()){
        $report = Model_Remote::xml_request('http://api-metrika.yandex.ru/stat/content/user_vars', $query + array
        (
            'id' => Kohana::$config->load('metrika.id'),
            'goal_id' => Kohana::$config->load('metrika.popstations_goal_id'),
            'table_mode' => 'tree',
            //per_page' => 7, per_page doesn't work as expected with tree_table_mode
        ));
        $report->registerXPathNamespace('y','http://api.yandex.ru/metrika/');

        $stations = $report->xpath("//y:row[descendant::y:name[.='forecast_query_json']]/y:chld/y:chld/y:name");

        $stations = array_map(function($v){
            if($v = json_decode((string)$v)){
                return $v->id;
            }
            return null;
        }, $stations);

        return array_filter($stations);
    }
}