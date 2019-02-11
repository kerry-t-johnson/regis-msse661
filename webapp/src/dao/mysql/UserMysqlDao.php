<?php


namespace msse661\dao\mysql;

use msse661\dao\UserDao;
use msse661\User;
use msse661\UserImpl;

class UserMysqlDao extends BaseMysqlDao implements UserDao
{

    public function create(array $userSpec): User {
        $query = <<<________QUERY
            INSERT INTO users
                        (id, email, first_name, last_name )
            VALUES      (':id', ':email', ':first_name', ':last_name')
________QUERY;

        $userSpec = $this->createEntity($userSpec, $query);

        return new UserImpl(['id' => $userSpec['id'], 'email' =>$userSpec['email'], 'first_name' => $userSpec['first_name'], 'last_name' => $userSpec['last_name']]);
    }

    public function getAll(int $offset = 0, int $limit = 0): array {
        $raw = $this->fetch('users', $offset, $limit);

        $entities = [];
        foreach($raw as $r) {
            $entities[] = new UserImpl($r);
        }
        return $entities;

    }

    public function getByEmail(string $email): User {
        return new UserImpl($this->fetchExactlyOne('users', 'email', $email));
    }

    public function getByUuid(string $uuid): User {
        return new UserImpl($this->fetchExactlyOne('users', 'id', $uuid));
    }

}