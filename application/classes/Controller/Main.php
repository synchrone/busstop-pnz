<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller {

    static $radius = 0.37;

    public function before(){
        $this->response->headers('Content-Type','application/json');
    }

    public function defuckify($sick_number){
        return (float)(substr($sick_number,0,2).'.'.substr($sick_number,2));
    }

    public function action_search_stations()
    {
        $c = Cache::instance();
        $stations = $c->get('stations');

        $found_stations = array_map(function($station)
        {
            return UTF8::strpos(
                strtolower($station['name']),
                strtolower(Request::current()->query('q'))
            ) !== false ? $station : null;
        },$stations);

        $found_stations = array_values(array_filter($found_stations));
        $this->response->body(json_encode($found_stations));
    }

	public function action_nearest_stations()
	{
        $r = Request::current();
        $c = Cache::instance();

		$lat = (float)$r->query('lat');
        $lon = (float)$r->query('lon');
        $accuracy = $r->query('accuracy'); //meters
        $stations = $c->get('stations');

        $loc = array_fill(0,count($stations),array('lat'=>$lat,'lon'=>$lon));

        $near_stations = array_map(function($station,$loc)
        {
            $station['lat0'] = $this->defuckify($station['lat0']);
            $station['lon0'] = $this->defuckify($station['lon0']);

            $inside = sqrt(
                sqrt(abs($station['lat0']-$loc['lat'])) + sqrt(abs($station['lon0']-$loc['lon']))
            ) <= self::$radius;

            return $inside ? $station : null;
        },$stations,$loc);
        $near_stations = array_values(array_filter($near_stations));

        $this->response->body(json_encode($near_stations));
    }

    public function action_forecast(){
        $forecast = Request::factory(Task_Fetch::BASE_URL.'getStationForecasts.php')
                    ->query(Request::current()->query() + array('city'=>'penza'))
                    ->execute();

        /** @var $forecast_xml SimpleXMLElement */
        $forecast_xml = simplexml_load_string($forecast);
        $forecast = array();
        foreach($forecast_xml->children() as $vehicle){
            $vehicle= (array)$vehicle;
            $forecast[] = $vehicle['@attributes'];
        }
        $this->response->body(json_encode($forecast));
    }

    public function action_index(){
        $this->response
            ->headers('Content-Type','text/html')
            ->body(View::factory('index')
        );
    }

} // End Welcome
