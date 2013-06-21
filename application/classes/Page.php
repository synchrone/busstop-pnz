<?php defined('SYSPATH') or die('No direct script access.');

class Page extends View
{
    public function __construct($page)
    {
        parent::__construct('page', array('content'=>$page, 'metrika_id' => Kohana::$config->load('metrika.id')));
    }
}