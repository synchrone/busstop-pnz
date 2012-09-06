<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller {

    const CITY = 'penza';

    //TODO: Variable search radius depending on accuracy
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
            return (
                UTF8::strpos(UTF8::strtolower($station['name']),
                    UTF8::strtolower(Request::current()->query('q'))
                ) !== false
            ) ? $station : null;
        },$stations);

        $found_stations = array_values(array_filter($found_stations));
        $this->response->body(json_encode($found_stations));
    }

	public function action_nearest_stations()
	{
        $c = Cache::instance();

        //$accuracy = Request::current()->query('accuracy'); //meters
        $near_stations = array_values(array_filter(array_map(function($station)
        {
            $r = Request::current();
            $loc = array('lat'=>(float)$r->query('lat'),'lon'=>(float)$r->query('lon'));

            $station['lat0'] = $this->defuckify($station['lat0']);
            $station['lon0'] = $this->defuckify($station['lon0']);

            //TODO: the earth is not a sphere, so this zone would be an oval
            $inside = sqrt(
                sqrt(abs($station['lat0']-$loc['lat'])) + sqrt(abs($station['lon0']-$loc['lon']))
            ) <= self::$radius;

            return $inside ? $station : null;
        },$c->get('stations'))));

        $this->response->body(json_encode($near_stations));
    }

    public function action_forecast(){
        $forecast = array();
        foreach(self::get_forecast_xml(Request::current()->query())->children() as $vehicle){
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

    public static function get_forecast_xml(array $query){
        /** @var $forecast Response */
        $forecast = Request::factory(Task_Fetch::BASE_URL.'getStationForecasts.php')
                    ->query($query + array('city'=>self::CITY))
                    ->execute();

        if($forecast->status() !== 200){
            throw HTTP_Exception::factory($forecast->status(),$forecast->body());
        }

        /** @var $forecast_xml SimpleXMLElement */
        $forecast_xml = simplexml_load_string($forecast->body());
        if($forecast_xml === false){
            throw new Kohana_Exception('Cannot parse forecast response for '.http_build_query($query));
        }
        return $forecast_xml;
    }

} // End Welcome
