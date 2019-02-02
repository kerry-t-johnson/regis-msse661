<?php


namespace msse661;


class UserImpl extends Entity implements User
{
    private const REQUIRED_KEYS = ['id', 'email', 'first_name', 'last_name'];

    public function __construct(array $userSpec) {
        self::assertRequiredSpec(self::REQUIRED_KEYS, $userSpec);

        parent::__construct($userSpec);
    }

    public function getEmail(): string {
        return $this->getAttributeValue('email');
    }

    public function getFirstName(): string {
        return $this->getAttributeValue('first_name');
    }

    public function getLastName(): string {
        return $this->getAttributeValue('last_name');
    }
}