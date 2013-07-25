<?php
define('EXT', '.php');
define('PRJROOT', realpath(__DIR__));
define('DOCROOT', realpath(PRJROOT.DIRECTORY_SEPARATOR.'www').DIRECTORY_SEPARATOR);
define('APPPATH', realpath(PRJROOT.DIRECTORY_SEPARATOR.'application').DIRECTORY_SEPARATOR);
define('MODPATH', realpath(PRJROOT.DIRECTORY_SEPARATOR.'modules').DIRECTORY_SEPARATOR);
define('SYSPATH', realpath(PRJROOT.DIRECTORY_SEPARATOR.'system').DIRECTORY_SEPARATOR);
define('KOHANA_START_TIME', microtime(TRUE));
define('KOHANA_START_MEMORY', memory_get_usage());

require APPPATH.'bootstrap'.EXT;