<?php


namespace msse661\controller;


use Monolog\Logger;
use msse661\dao\mysql\UserMysqlDao;
use msse661\dao\UserDao;
use msse661\User;
use msse661\util\logger\LoggerManager;
use msse661\view\ViewFactory;

class UserController extends BaseController implements Controller {

    /** @var UserDao */
    private $userDao;

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
        parent::__construct('user');
    }

    public function route(array $request) : string {
        $user   = $this->getResource($request);

        return ViewFactory::render(
            'user',
            ['user' => $user],
            $request['query']['view'] ?? (is_array($user) ? 'list' : null));
    }

    public function onPostLogin(array $request) {
        $userDao    = new UserMysqlDao();
        $user       = $userDao->getByEmailAndPassword($_POST['email'], $_POST['password']);

        $this->login($user);

        $this->redirect("/");
    }

    public function login(User $user) : void {
        self::startUserSession($user);
    }

    public function onPostLogout(array $path, array $query = []) {
        self::destroyUserSession();

        $this->redirect("/");
    }

    public function onGetRegister(array $path, array $query = []) : string {
        return ViewFactory::render('user', [], 'register');
    }

    public function onPostRegistration(array $path, array $query = []) {
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
            $this->login($user);

            return ViewFactory::render('user', ['user' => $user]);
        }
    }
}
