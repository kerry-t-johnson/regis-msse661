<?php


namespace msse661\dao;


use msse661\User;

interface UserDao
{

    function create(array $userSpec): User;

    function getAll(int $offset = 0, int $limit = 0): array;

    function getByUuid(string $uuid): User;

    function getByEmail(string $email): User;

}