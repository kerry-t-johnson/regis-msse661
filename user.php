<?php

require_once 'bootstrap.php';

/** @var \Monolog\Logger $logger */
$logger = \msse661\util\logger\LoggerManager::getLogger('user.php');

\msse661\controller\UserController::startUserSession();

try {
    $result = \msse661\controller\SiteController::route();

    if(is_string($result)) {
        print $result;
    }
    else {
        print \msse661\view\ViewFactory::render('user', ['user' => $result], is_array($result) ? 'list' : '');
    }
}
catch (Exception $ex) {
    $logger->error('route', ['exception' => $ex]);

    // TODO: Show error page
}

