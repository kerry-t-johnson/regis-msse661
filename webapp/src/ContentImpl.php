<?php


namespace msse661;


use msse661\dao\CommentDao;
use msse661\dao\EntityDao;
use msse661\dao\EntityDaoFactory;
use msse661\dao\mysql\CommentMysqlDao;
use msse661\dao\mysql\TagMysqlDao;
use msse661\dao\TagDao;

class ContentImpl extends EntityImpl implements Content {

    private const REQUIRED_KEYS = ['id', 'title', 'users', 'state', 'path', 'mime_type', 'hash'];
    private const HIDDEN_KEYS   = ['state', 'comments_allowed'];

    public function __construct(array $contentSpec) {
        parent::__construct('content', $contentSpec, self::REQUIRED_KEYS, self::HIDDEN_KEYS);

        /** @var EntityDao $userDao */
        $userDao = EntityDaoFactory::createEntityDao('user');
        $this->values['user'] = $userDao->fetchExactlyOne('id', $this->getUserUuid());

        /** @var TagDao $tagDao */
        $tagDao = new TagMysqlDao();
        $this->values['tags'] = $tagDao->getTagsByContent($this->getUuid());

        /** @var CommentDao $commentDao */
        $commentDao = new CommentMysqlDao();
        $this->values['comment_count'] = $commentDao->countByContent($this->getUuid());

        if($this->getMimeType() == 'text/plain') {
            $this->values['html'] = file_get_contents($this->getPath());
        }
        else if($this->getMimeType() == 'application/pdf') {
            $this->values['html'] =<<<IFRAME
                <iframe src="/{$this->getPath()}"
                        style="width:728px; height:700px;"
                        frameborder="0">
                </iframe>
IFRAME;
        }
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
        return $this->getAttributeValue('user');
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
        return $this->getAttributeValue('tags');
    }

}