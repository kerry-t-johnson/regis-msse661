<?php


namespace msse661;


class UserImpl extends EntityImpl implements User
{
    private const REQUIRED_KEYS = ['id', 'email', 'first_name', 'last_name'];

    public function __construct(array $userSpec) {
        parent::__construct($userSpec, self::REQUIRED_KEYS);
    }

    public function getFullName(): string {
        return $this->getFirstName() . ' ' . $this->getLastName();
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

    public function verifyPassword(string $password): void {
        $hashed_password    = $this->getAttributeValue('hashed_password');

        if(!password_verify($password, $hashed_password)) {
            throw new \Exception('Unable to veirify password for user: ' . $this->getFullName());
        }
    }
}