<?php defined('SYSPATH') or die('No direct script access.');

class Model
{
    public static function factory($data)
    {
        $clsname = get_called_class();
        $object = new $clsname();
        foreach($data as $key=>$value){
            $object->$key = $value;
        }
        return $object;
    }
    public function __get($name){
        return null;
    }
}
