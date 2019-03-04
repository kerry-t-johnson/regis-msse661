<?php


namespace msse661;


use msse661\dao\EntityDao;
use msse661\dao\EntityDaoFactory;
use msse661\dao\mysql\TagMysqlDao;
use msse661\dao\TagDao;

class ContentImpl extends EntityImpl implements Content {

    private const REQUIRED_KEYS = ['id', 'title', 'users', 'state', 'path', 'mime_type', 'hash'];
    private const HIDDEN_KEYS   = ['state', 'comments_allowed'];

    /** @var User */
    private $user;

    /** @var array */
    private $comments;

    public function __construct(array $contentSpec) {
        parent::__construct('content', $contentSpec, self::REQUIRED_KEYS, self::HIDDEN_KEYS);
    }

    public function getTitle(): string {
        return $this->getAttributeValue('title');
    }

    public function getDescription(): string {
        return $this->getAttributeValue('description', false) ?? '';
    }

    public function getUserUuid(): string {
        return $this->getAttributeValue('users');
    }

    public function getUser(): User {
        if(!$this->user) {
            /** @var EntityDao $userDao */
            $userDao = EntityDaoFactory::createEntityDao('user');
            $this->user = $userDao->fetchExactlyOne('id', $this->getUserUuid());
        }

        return $this->user;
    }

    public function getComments(): array {
        if(!$this->comments) {
            /** @var EntityDao $commentDao */
            $commentDao = EntityDaoFactory::createEntityDao('comment');
            $this->comments = $commentDao->fetchWhere("content = ':content'", ['content' => $this->getUuid()]);
        }

        return $this->comments;
    }

    public function getState(): string {
        return $this->getAttributeValue('state');
    }

    public function getPath(): string {
        return $this->getAttributeValue('path');
    }

    public function getContent(): string {
        return file_get_contents('http://loripsum.net/api/' . $this->getPath());
    }

    public function getHash(): string {
        return $this->getAttributeValue('hash');
    }

    public function getMimeType(): string {
        return $this->getAttributeValue('mime_type');
    }

    public function getImageType(): int {
        return exif_imagetype($this->getFullPath());
    }

    public function getFullPath(): string {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->getPath();
    }

    public function getTags(): array {
        /** @var TagDao $tagDao */
        $tagDao = new TagMysqlDao();

        return $tagDao->getTagsByContent($this->getUuid());
    }

}