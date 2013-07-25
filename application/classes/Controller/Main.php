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
           ($lat = $r->query('latitude')) &&
           ($lon = $r->query('longitude')) &&
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
        $this->response
            ->nocache()
            ->html(
                View::factory('station_forecast')
                ->set('forecast',
                    Model_Remote::station_forecast($this->request->query())
                )
                ->set('station', Model_Station::by_id($this->request->query('id'),true))
            )
        ;
    }

    public function action_vehicle_forecast()
    {
        $this->response
            ->nocache()
            ->html(
                View::factory('vehicle_forecast')
                ->set('forecast',
                    Model_Remote::vehicle_forecast(
                        Arr::extract($this->request->query(), array('id','type'))
                    )
                )
                ->set('title', $this->request->query('title'))
            )
        ;
    }

    public function action_about()
    {
        $this->response->html(View::factory('about'));
    }
} // End Welcome
