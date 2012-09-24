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
        /** @var $response Response */
        $response = Request::factory(self::BASE_URL.$url)
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
     * @param $route_id2
     * @return SimpleXMLElement|Model_Station[]
     */
    public static function stations($route_id,$route_id2=null)
    {
        $stations = self::xml_request('getRouteStations.php',array(
            'type'=>0,
            'id1'=>(int)$route_id,
            'id2'=>(int)($route_id2 === null ? $route_id : $route_id2)
        ));
        return $stations;

        $data = array();
        foreach($stations->children() as $station){
            $station = (array)$station;
            /** @var $station Model_Station */
            $station = Model_Station::factory($station['@attributes']);
            $data[$station->id] = $station;
            unset($station);
        }
        unset($stations);
        return $data;
    }

    public static function forecast($query = array()){
        $xml_vehicles = self::xml_request('getStationForecasts.php',$query);
        $forecast = array();
        foreach($xml_vehicles->children() as $vehicle){
            $vehicle= (array)$vehicle;
            $forecast[] = $vehicle['@attributes'];
        }
        return $forecast;
    }
}