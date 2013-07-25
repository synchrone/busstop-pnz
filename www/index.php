<?php
$root = realpath(__DIR__.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR;
include $root.'env.php';

/**
 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
 * If no source is specified, the URI will be automatically detected.
 */
echo Request::factory(TRUE, array(), FALSE)
    ->execute()
    ->send_headers(TRUE)
    ->body();
