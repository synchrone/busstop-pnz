<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller
{
    public function before()
    {
        $this->response->headers('Content-Type','text/html');
    }
    public function after()
    {
        if($this->response->headers('Content-Type')=='text/html' && !$this->request->is_ajax())
        {
            //wrap in normal page for non-ajax responses with default content-type intact
            $this->response->body(new Page($this->response->body()));
        }

        $this->check_cache(); //apply body-hash e-taggin'
    }


    public function action_index()
   	{
        $r = $this->request;
        $content = array();

        if($favorite_ids = $r->query('favorite')){
            $content['favorite'] = Model_Station::by_id($favorite_ids);
        }

        if(
           ($lat = (float)$r->query('latitude')) &&
           ($lon = (float)$r->query('longitude')) &&
           ($accuracy = $r->query('accuracy'))
        ){
           $content['nearest'] = Model_Station::nearest($lat, $lon, $accuracy);
        }

        if(
           count(Arr::get($content,'nearest', array())) == 0 &&
           count(Arr::get($content,'favorite', array())) == 0
        ){
            $content['popular'] = Model_Station::popular();
        }

        $content = View::factory('inc/default-items')->set($content);

        if(!$this->request->is_ajax()){
           $content = View::factory('search',array('content'=>$content));
        }

        $this->response->html($content);
    }

    public function action_search_stations()
    {
        $q = trim($this->request->query('q'));
        $result = array(
            'stations' => Model_Station::search($q, Model_Station::MATCH_ALL),
            'query' => $q,
            'fixed_query' => null
        );

        if(count($result['stations']) == 0) //typo ?
        {
            $result['fixed_query'] = Model_DidYouMean::instance()->fix($q);

            if($result['fixed_query'] != null) //there were replacements made
            {
                $result['stations'] = Model_Station::search(
                    $result['fixed_query'],
                    Model_Station::MATCH_ANY
                );
            }
        }
        $result['results'] = View::factory('inc/search-items')
            ->set('items',$result['stations'])->render();
        unset($result['stations']);
        $this->response->json($result);
    }

    public function action_station_forecast()
    {
        $forecast = Model_Remote::station_forecast($this->request->query());
        $station = Model_Station::by_id($this->request->query('sid'), true);

        $view = View::factory('station_forecast')
            ->set('forecast', $forecast)
            ->set('station', $station)
            ->set('vehicles_enroute', null);

        //OK, no forecast, how about vehicles en route ?
        //TODO: somehow find what are the routes passing thru station from the new api
//        if(empty($forecast) && ($passing_routes = $station->passing_routes()))
//        {
//            $vehicles = Model_Remote::vehicles($passing_routes);
//            $view->set('vehicles_enroute', count($vehicles));
//        }

        $this->response
            ->nocache()
            ->html($view)
        ;
    }

    public function action_vehicle_forecast()
    {
        $this->response
            ->nocache()
            ->html(
                View::factory('vehicle_forecast')
                ->set('forecast',
                    Model_Remote::vehicle_forecast($this->request->query())
                )
                ->set('title', $this->request->query('title'))
            )
        ;
    }

    public function action_about()
    {
        $view = View::factory('about');

        $vcounts = array();
        $available_vehicles = Model_Remote::vehicles();
        foreach($available_vehicles as $vehicle){
            $vcount = Arr::get($vcounts, $vehicle->rtype, 0);
            Arr::set_path($vcounts, $vehicle->rtype, ++$vcount);
        }

        $view
            ->set('vcounts', $vcounts)
            ->set('rtypes', array_keys($vcounts));

        $this->response->html($view);
    }
} // End Welcome
