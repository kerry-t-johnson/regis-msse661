<?php


namespace msse661\controller;


use msse661\dao\mysql\UserMysqlDao;
use msse661\dao\UserDao;
use msse661\view\ViewFactory;

class UserController implements Controller {

    /** @var UserDao */
    private $userDao;

    public function __construct() {
        $this->userDao = new UserMysqlDao();
    }

    public function route(array $path, array $query = []) : string {
        $userUuid   = $path[0] ?? null;

        if($userUuid) {
            $user = $this->userDao->getByUuid($userUuid);

            return ViewFactory::render('user', ['user' => $user], $query['view'] ?? null);
        }
        else {
            // TODO Use offset/limit (basically, a pager)
            $users = $this->userDao->getAll();
            return ViewFactory::render('user', ['users' => $users], $query['view'] ?? 'list');
        }
    }

}
