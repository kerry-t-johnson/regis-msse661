<?php


namespace msse661\dao\mysql;

use msse661\dao\UserDao;
use msse661\User;
use msse661\util\logger\LoggerManager;
use msse661\UserImpl;

class UserMysqlDao extends BaseMysqlDao implements UserDao
{
    private $logger;

    public function __construct() {
        parent::__construct();

        $this->logger = LoggerManager::getLogger(basename(__FILE__, '.php'));
    }

    public function create(array $userSpec): User {
        $userSpec['id'] = self::newUuid();
        $query          = "INSERT INTO users (id, email, first_name, last_name ) VALUES (':id', ':email', ':first_name', ':last_name')";
        $query          = $this->escapeQuery($query, $userSpec);

        if ($this->query($query) === true) {
            $this->logger->info("Created user", $userSpec);
            return new UserImpl(['id' => $userSpec['id'], 'email' =>$userSpec['email'], 'first_name' => $userSpec['first_name'], 'last_name' => $userSpec['last_name']]);
        } else {
            throw new \Exception('createUser: caught exception ' . $this->error . '\n');
        }
    }

    public function getByEmail(string $email): ?User {
        return $this->get($email, 'email');
    }

    public function getByUuid(string $uuid): ?User {
        return $this->get($uuid, 'id');
    }

    private function get(string $value, string $key): ?User {
        $query = "SELECT * FROM users WHERE {$key} = '{$value}'";

        $result = $this->query($query);

        if($result->num_rows == 0) {
            return null;
        }
        else if($result->num_rows > 1) {
            throw new \Exception("Expected one object to match {$key} = {$value}.  Found {$result->num_rows} objects.");
        }

        return new UserImpl($result->fetch_assoc());
    }
}