<?php defined('SYSPATH') or die('No direct script access.');

class Response extends Kohana_Response
{

    /**
     * @return $this
     */
    public function nocache()
    {
        return $this
            ->headers('Cache-Control','no-cache') //HTTP/1.1
            ->headers('Expires','Thu, 01 Dec 1994 16:00:00 GMT') //HTTP/1.0 style
        ;
    }

    public function json($data)
    {
        return $this
            ->headers('Content-Type','application/json')
            ->body(json_encode($data));
    }

    public function html($data)
    {
        return $this
            ->headers('Content-Type','text/html')
            ->body($data);
    }
}