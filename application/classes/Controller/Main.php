<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller
{
    const CITY = 'penza';

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
    }
    public function json_response($data)
    {
        return $this->response
            ->headers('Content-Type','application/json')
            ->body(json_encode($data));
    }

    /**
     * @param $data
     * @return Response
     */
    public function html_response($data){
        return $this->response
            ->headers('Content-Type','text/html')
            ->body($data);
    }

    public function action_index()
   	{
       $r = $this->request;
       $content = View::factory('inc/default-items')
           ->set('favorite',
               Model_Station::by_id($r->query('favorite'))
           );

       if(
           ($lat = $r->query('latitude')) &&
           ($lon = $r->query('longitude')) &&
           ($accuracy = $r->query('accuracy'))
       ){
           $content->set('nearest',
               Model_Station::nearest(
                   $lat, $lon, $accuracy
               )
           );
       }
       if(!$this->request->is_ajax() || !count($this->request->query())){
           $content = View::factory('search',array('content'=>$content));
       }
       $this->html_response($content);
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
        $result['results'] = View::factory('inc/search-items')
            ->set('items',$result['stations'])->render();
        unset($result['stations']);
        $this->json_response($result);
    }

    public function action_forecast()
    {
        $this->html_response(
            View::factory('forecast')
            ->set('forecast',
                Model_Remote::forecast($this->request->query())
            )
            ->set('station',Model_Station::by_id($this->request->query('id'),true))
        )
        ->headers('Cache-Control','no-cache') //HTTP/1.1
        ->headers('Expires','Thu, 01 Dec 1994 16:00:00 GMT') //HTTP/1.0 style
        ;
    }

    public function action_about(){
        $this->html_response(View::factory('about'));
    }
} // End Welcome
