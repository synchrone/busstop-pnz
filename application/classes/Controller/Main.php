<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller {

    const CITY = 'penza';

    public function before(){
        $this->response->headers('Content-Type','application/json');
    }

    public function action_index(){
        $this->response
            ->headers('Content-Type','text/html')
            ->body(View::factory('index')
        );
    }

    public function action_search_stations()
    {
        $q = trim(Request::current()->query('q'));
        $result = array(
            'stations' => Model_Station::search($q),
            'query' => $q,
            'fixed_query' => null
        );

        if(count($result['stations']) == 0){ //typo ?
            $result['fixed_query'] = Model_DidYouMean::instance()->fix($q);
            if($result['fixed_query'] != null){
                $result['stations'] = Model_Station::search($result['fixed_query']);
            }
        }

        $this->response->body(json_encode($result));
    }

	public function action_nearest_stations()
	{
        $r = Request::current();
        $this->response->body(
            json_encode(
                Model_Station::nearest($r->query('lat'),$r->query('lon'))
            )
        );
    }

    public function action_forecast(){
        $forecast = array();
        foreach(self::get_forecast_xml(Request::current()->query())->children() as $vehicle){
            $vehicle= (array)$vehicle;
            $forecast[] = $vehicle['@attributes'];
        }
        $this->response->body(json_encode($forecast));
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
