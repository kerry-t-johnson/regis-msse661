<?php


namespace msse661\dao\mysql;

use msse661\dao\UserDao;
use msse661\User;
use msse661\UserImpl;

class UserMysqlDao extends BaseMysqlDao implements UserDao
{

    public function __construct() {
        parent::__construct('users', '\\msse661\\UserImpl', 'last_name ASC');
    }

    public function create(array $userSpec): User {
        $query = <<<________QUERY
            INSERT INTO users
                        (id,    email,    first_name,    last_name,     hashed_password )
            VALUES      (:id,  :email,   :first_name,   :last_name,    :hashed_password)
________QUERY;

        $userSpec['hashed_password'] = password_hash($userSpec['password'], PASSWORD_DEFAULT);
        $userSpec = $this->createEntity($userSpec, $query);

        return new UserImpl(['id' => $userSpec['id'], 'email' =>$userSpec['email'], 'first_name' => $userSpec['first_name'], 'last_name' => $userSpec['last_name']]);
    }

    public function getAll(int $offset = 0, int $limit = 0): array {
        return $this->fetch($offset, $limit);
    }

    public function getByEmail(string $email): User {
        return $this->fetchExactlyOne('email', $email);
    }

    public function getByEmailAndPassword(string $email, string $password): User {
        $user = $this->getByEmail($email);
        $user->verifyPassword($password);

        return $user;
    }

    public function getByUuid(string $uuid): User {
        return $this->fetchExactlyOne('id', $uuid);
    }

}