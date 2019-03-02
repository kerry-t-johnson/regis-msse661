<?php


namespace msse661\controller;


use Monolog\Logger;
use msse661\dao\mysql\UserMysqlDao;
use msse661\dao\UserDao;
use msse661\PianoException;
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

    public function render($user, $view = null) : string {
        return ViewFactory::render(
            'user',
            ['user' => $user],
            $view ?? (is_array($user) ? 'list' : null));
    }

    public function onPostLogin(array $request) {
        $userDao    = new UserMysqlDao();
        $user       = $userDao->getByEmailAndPassword($_POST['email'], $_POST['password']);

        $this->login($user);

        return $user;
    }

    public function login(User $user) : void {
        self::startUserSession($user);
    }

    public function onPostLogout(array $request) {
        self::destroyUserSession();

        return true;
    }

    public function onGetRegister(array $request) : string {
        return ViewFactory::render('user', [], 'register');
    }

    public function onPostRegister(array $request) {
        $this->logger->debug('onPostRegister', ['user_email' => $_POST['email']]);
        $userDao        = new UserMysqlDao();
        $user_email     = $_POST['email'] ?? null;
        $user_password  = $_POST['password'] ?? null;

        try {
            $this->logger->debug('onPostRegister1');
            $userDao->getByEmail($user_email);

            throw new PianoException("User with that email address already exists: {$user_email}", 403);
        }
        catch(\Exception $ex) {
            $this->logger->debug('onPostRegister2');
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                // TODO Generate form error
                $this->logger->debug('onPostRegister3');
            }

            $userSpec =[
                'email'         => $user_email,
                'password'      => $user_password,
                'first_name'    => $_POST['first_name'],
                'last_name'     => $_POST['last_name'],
            ];

            $user   = $userDao->create($userSpec);
            $this->login($user);

            $this->logger->debug('onPostRegister', ['user' => $user]);

            return $user;
        }
    }
}
