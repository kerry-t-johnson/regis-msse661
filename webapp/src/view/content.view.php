<?php
/** @var \msse661\Content $content */

/** @var \msse661\User $user */
$user = $content->loaded['user'] ?? null;

if(!$user) {
    $userDao = new \msse661\dao\mysql\UserMysqlDao();
    $user = $userDao->getByUuid($content->getUserUuid());
}

?>

