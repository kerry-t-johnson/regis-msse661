<?php


namespace msse661\controller;


use Monolog\Logger;
use msse661\dao\mysql\UserMysqlDao;
use msse661\dao\UserDao;
use msse661\User;
use msse661\util\logger\LoggerManager;
use msse661\view\ViewFactory;

class UserController implements Controller {

    /** @var UserDao */
    private $userDao;

    /** @var Monolog\Logger */
    private $logger;

    public static function getCurrentUser(): ?User {
        return $_SESSION['user'] ?? null;
    }

    public static function startUserSession(User $user = null): void {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if($user) {
            $_SESSION['user'] = $user;
        }
    }

    public static function destroyUserSession(): void {
        session_destroy();
        $_SESSION = [];
    }

    public function __construct() {
        $this->userDao  = new UserMysqlDao();
        $this->logger   = LoggerManager::getLogger('UserController');
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

    public function login(array $path, array $query = []) {
        $userDao    = new UserMysqlDao();
        $user       = $userDao->getByEmailAndPassword($_POST['email'], $_POST['password']);

        $this->onLogin($user);

        SiteController::redirect("/");
    }

    public function onLogin(User $user) : void {
        self::startUserSession($user);
    }

    public function logout(array $path, array $query = []) {
        self::destroyUserSession();

        SiteController::redirect("/");
    }

    public function register(array $path, array $query = []) : string {
        return ViewFactory::render('user', [], 'register');
    }

    public function onRegistration(array $path, array $query = []) {
        $userDao        = new UserMysqlDao();
        $user_email     = $_POST['email'] ?? null;
        $user_password  = $_POST['password'] ?? null;

        try {
            $userDao->getByEmail($user_email);

            // User already exists
            // TODO generate error
        }
        catch(\Exception $ex) {
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                // TODO Generate form error
            }

            $userSpec =[
                'email'         => $user_email,
                'password'      => $user_password,
                'first_name'    => $_POST['first_name'],
                'last_name'     => $_POST['last_name'],
            ];

            $user   = $userDao->create($userSpec);
            $this->onLogin($user);

            return ViewFactory::render('user', ['user' => $user]);
        }
    }
}
