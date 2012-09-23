<?php defined('SYSPATH') OR die('No direct script access.');

class UTF8 extends Kohana_UTF8
{
   	public static function stripos($str, $search, $offset = 0)
   	{
   		return UTF8::strpos(UTF8::strtolower($str), UTF8::strtolower($search), $offset);
   	}
}
