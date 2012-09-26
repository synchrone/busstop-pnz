<?php defined('SYSPATH') or die('No direct script access.');

class Text extends Kohana_Text
{
    public static function split_words($words)
    {
        return preg_split('/[[:space:]-._]/',$words);
    }

    public static function minutes($seconds){
        return round(((float)$seconds) / 60).' мин.';
    }
}