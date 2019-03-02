<?php

require_once 'bootstrap.php';

/** @var \Monolog\Logger $logger */
$logger = \msse661\util\logger\LoggerManager::getLogger('index.php');

\msse661\controller\UserController::startUserSession();

try {
    // TODO: This is inelegant :(
    if(strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
        $content    = \msse661\controller\SiteController::route('/content?view=portfolio');
        $tags       = \msse661\controller\SiteController::route('/tag?view=button-group');

        print \msse661\view\ViewFactory::render('front-page', ['data' => $content, 'tag_content' => $tags]);
    }
    else {
        \msse661\controller\SiteController::route();
    }
}
catch (Exception $ex) {
    $logger->error('route', ['exception' => $ex]);

    // TODO: Show error page
}

