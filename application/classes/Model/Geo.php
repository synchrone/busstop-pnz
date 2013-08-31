<?php defined('SYSPATH') or die('No direct script access.');

abstract class Model_Geo extends Model
{
    protected function floatify($buspnz_latlon){
        return (float)(substr($buspnz_latlon,0,2).'.'.substr($buspnz_latlon,2));
    }
}