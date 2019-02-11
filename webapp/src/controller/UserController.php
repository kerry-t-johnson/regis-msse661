<?php


namespace msse661\controller;


use msse661\dao\mysql\UserMysqlDao;
use msse661\dao\UserDao;

class UserController implements Controller {

    /** @var UserDao */
    private $userDao;

    public function __construct() {
        $this->userDao = new UserMysqlDao();
    }

    public function route(array $path, array $query = []) {
        $userUuid   = $path[0] ?? null;

        if($userUuid) {
            $user = $this->userDao->getByUuid($userUuid);
        }
    }

}