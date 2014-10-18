<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Model_Remote extends Model
{
    const CITY = 'penza';
    const BASE_URL = 'http://58bus.ru/php/';

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

    /**
     * @param $url
     * @param array $query_params
     * @return stdClass[]|stdClass
     * @throws Kohana_Exception
     */
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
        $data = self::json_request('getRoutes.php');
        $routes = array();
        foreach($data as $route)
        {
            $routes[] = Model_Route::factory((array) $route);
        }
        return $routes;
    }

    /**
     * @param Model_Route[]|array|string $routes
     * @return Model_Vehicle[]
     */
    public static function vehicles($routes = null){
        if(empty($routes)){
            $routes = self::routes();
        }

        if(is_array($routes)){
            if(current($routes) instanceof Model_Route){
                $ids = array();
                foreach($routes as $route){
                    /** @var Model_Route $route */
                    $ids[] = sprintf('%d-0', $route->id);
                }
                $routes = $ids; unset($ids);
            }
            $routes = implode(',',$routes);
        }

        $params = array(
            'rids'=>$routes,
            'lat0'=>0, 'lng0'=>0, 'lat1'=>90, 'lng1'=>180, 'curk'=>0
        );
        $json_vehicles = self::json_request('getVehiclesMarkers.php', $params)->anims;

        $forecast = array();
        foreach($json_vehicles as $vehicle){
            $forecast[] = Model_Vehicle::factory($vehicle);
        }
        return $forecast;
    }

    /**
     * @return Model_Station[]
     */
    public static function stations() {
        $json_stations = self::json_request('getStations.php');

        return array_map(function($json_station){
            return Model_Station::factory((array)$json_station);
        }, $json_stations);
    }

    /**
     * @param array $query
     * @return Model_Forecast_Vehicle[]
     */
    public static function station_forecast($query = array()){
        $json_vehicles = self::json_request('getStationForecasts.php', $query);
        $forecast = array();
        foreach($json_vehicles as $vehicle){
            $forecast[] = Model_Forecast_Vehicle::factory($vehicle);
        }
        return $forecast;
    }

    public static function vehicle_forecast($query = array()){
        $json_stations = self::json_request('getVehicleForecasts.php',$query);
        $forecast = array();
        foreach($json_stations as $stations_forecast){
            $forecast[] = Model_Forecast_Station::factory($stations_forecast);
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
