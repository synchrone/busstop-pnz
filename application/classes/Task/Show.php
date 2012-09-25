<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 */
class Task_Show extends Minion_Task
{
    protected $_options = array('key'=>null);
    protected function _execute(array $params)
    {
        var_export(Cache::instance()->get($params['key']));
    }
}