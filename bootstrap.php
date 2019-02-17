<?php

$index_root     = str_replace('\\', '/', dirname(__FILE__));
$webapp_root    = $index_root . '/webapp';
set_include_path(get_include_path() . PATH_SEPARATOR . $webapp_root);

require_once 'vendor/autoload.php';
