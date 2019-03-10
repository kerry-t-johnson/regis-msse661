<?php


namespace msse661\controller;


use Monolog\Logger;
use msse661\dao\EntityDaoFactory;
use msse661\dao\mysql\ContentMysqlDao;
use msse661\dao\mysql\TagMysqlDao;
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
        $logger = LoggerManager::getLogger('UserController');
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
        $this->userDao = EntityDaoFactory::createEntityDao('user');
    }

    public function render($user, $view = null) : string {
        return ViewFactory::render(
            'user',
            ['user' => $user],
            $view ?? (is_array($user) ? 'list' : null));
    }

    public function onGetContent(string $resource, array $request) {
        $this->logger->info('onGetContent', ['resource' => $resource]);
        $contentDao = new ContentMysqlDao();
        return $contentDao->getByUser(
            $resource,
            $request['query']['offset'] ?? 0,
            $request['query']['limit'] ?? 0);
    }

    public function onGetTag(string $resource, array $request) {
        $this->logger->debug('onGetTag', ['resource' => $resource, 'request' => $request]);
        $tagDao = new TagMysqlDao();
        return $tagDao->getTagsByUser($resource);
    }

    public function onGetCheck(array $request) {
        $email = $request['query']['email'] ?? null;
        $email = $email ? urldecode($email) : null;
        $this->logger->info('onGetCheck', ['email' => $email]);

        try {
            $this->userDao->getByEmail($email);
            return 'That email address is already used.';
        }
        catch(\Exception $ex) {
            return "true";
        }
    }

    public function onPostTag(string $resource, array $request) {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->logger->debug('onPostTag', ['resource' => $resource, 'request' => $request, 'post' => $_POST, 'data' => $data]);

        $tagDao = new TagMysqlDao();
        foreach($data as $tagCheckbox) {
            $this->logger->debug('Applying checkbox change: ', ['checkbox' => $tagCheckbox]);
            if($tagCheckbox['value']) {
                $tagDao->saveUserTags($resource, $tagCheckbox['uuid']);
            }
            else {
                $tagDao->clearUserTags($resource, $tagCheckbox['uuid']);
            }
        }

        return $tagDao->getTagsByUser($resource);
    }

    public function onPostLogin(array $request) {
        $userDao    = new UserMysqlDao();
        $user       = $userDao->getByEmailAndPassword($_POST['email'], $_POST['password']);

        $this->login($user);

        $this->redirect('user/' . $user->getUuid());

        return $user;
    }

    public function login(User $user) : void {
        self::startUserSession($user);
    }

    public function onGetLogout(array $request) {
        return $this->onPostLogout($request);
    }

    public function onPostLogout(array $request) {
        self::destroyUserSession();

        $this->redirect();

        return true;
    }

    public function onGetLogin(array $request) : string {
        return ViewFactory::render('user', [], 'login');
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

            $this->redirect('user/' . $user->getUuid());

            return $user;
        }
    }
}
