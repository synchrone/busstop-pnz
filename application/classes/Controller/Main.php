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

    public function action_favorite(){
        //TODO: favorite stations
    }

    public function action_search_stations()
    {
        $q = trim($this->request->query('q'));
        $result = array(
            'stations' => Model_Station::search($q,Model_Station::MATCH_ALL),
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

        $this->response->body(json_encode($result));
    }

	public function action_nearest_stations()
	{
        $r = $this->request;
        $this->response->body(
            json_encode(
                Model_Station::nearest($r->query('lat'),$r->query('lon'))
            )
        );
    }

    public function action_forecast()
    {
        $this->response
            ->headers('Cache-Control','no-cache') //HTTP/1.1
            ->headers('Expires','Thu, 01 Dec 1994 16:00:00 GMT') //HTTP/1.0 style
            ->body(json_encode(Model_Remote::forecast($this->request->query())));
    }
} // End Welcome
