<?php


namespace msse661;


interface User extends Entity
{
    public function getFullName(): string;

    public function getEmail(): string;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function verifyPassword(string $password): void;

}