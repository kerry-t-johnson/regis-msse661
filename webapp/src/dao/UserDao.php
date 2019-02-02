<?php


namespace msse661\dao;


use msse661\User;

interface UserDao
{

    function create(array $userSpec): User;

    function getByUuid(string $uuid): ?User;

    function getByEmail(string $uuid): ?User;

}