<?php

require_once 'bootstrap.php';

/** @var \Monolog\Logger $logger */
$logger = \msse661\util\logger\LoggerManager::getLogger('api.php');

\msse661\controller\UserController::startUserSession();

try {
    \msse661\controller\SiteController::routeApi();
}
catch(Exception $ex) {
    $logger->error('route', ['exception' => $ex]);

    throw $ex;
}
