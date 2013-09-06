<?php defined('SYSPATH') or die('No direct script access.');

class Arr extends Kohana_Arr
{
    public static function opluck($array, $key){
        $values = array();

        foreach ($array as $row)
        {
            if (property_exists($row,$key))
            {
                // Found a value in this row
                $values[] = $row->$key;
            }
        }

        return $values;
    }
}