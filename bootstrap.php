<?php

$index_root     = str_replace('\\', '/', dirname(__FILE__));
$webapp_root    = $index_root . '/webapp';
set_include_path(get_include_path() . PATH_SEPARATOR . $webapp_root);
set_include_path(get_include_path() . PATH_SEPARATOR . $index_root);

$ini = parse_ini_file('site.ini', true);
foreach($ini as $section => $keyValue) {
    foreach($keyValue as $key => $value) {
        if(!isset($GLOBALS["{$section}.{$key}"])) {
            $GLOBALS["{$section}.{$key}"] = $value;
        }
    }
}

require_once 'vendor/autoload.php';
