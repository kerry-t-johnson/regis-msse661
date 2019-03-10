<?php

require_once 'bootstrap.php';

/** @var \Monolog\Logger $logger */
$logger = \msse661\util\logger\LoggerManager::getLogger('index.php');

\msse661\controller\UserController::startUserSession();

$user = \msse661\controller\UserController::getCurrentUser();

setcookie('user', $user ? json_encode($user) : null);

try {
    print print \msse661\controller\SiteController::route();
}
catch (Exception $ex) {
    $content    = \msse661\controller\SiteController::route('/content?view=portfolio-list');
    $tags       = \msse661\controller\SiteController::route('/tag?view=button-group');

    print \msse661\view\ViewFactory::render('front-page', ['content' => $content, 'tag_content' => $tags]);

    $logger->error('route', ['exception' => $ex]);
}

