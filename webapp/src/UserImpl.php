<?php


namespace msse661;


use msse661\dao\mysql\TagMysqlDao;

class UserImpl extends EntityImpl implements User
{
    private const REQUIRED_KEYS = ['id', 'email', 'first_name', 'last_name'];
    private const HIDDEN_KEYS   = ['hashed_password'];

    public function __construct(array $userSpec) {
        parent::__construct('user', $userSpec, self::REQUIRED_KEYS, self::HIDDEN_KEYS);
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
            throw new PianoException('Unable to verify password for user: ' . $this->getFullName(), 401);
        }
    }

    public function getTags(bool $assoc = false): array {
        /** @var TagDao $tagDao */
        $tagDao = new TagMysqlDao();

        return $tagDao->getTagsByUser($this->getUuid(), $assoc);
    }

}