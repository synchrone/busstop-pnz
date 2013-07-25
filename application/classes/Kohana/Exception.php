<?php defined('SYSPATH') or die('No direct script access.');

//Pre-init call to this class occur without autoload support
/** @noinspection PhpIncludeInspection */
require_once MODPATH.'minion/classes/Kohana/Minion/Exception.php';
/** @noinspection PhpIncludeInspection */
require_once MODPATH.'minion/classes/Minion/Exception.php';

class Kohana_Exception extends Minion_Exception{

}