<?php


namespace msse661;


use msse661\dao\CommentDao;
use msse661\dao\EntityDaoFactory;

class CommentImpl extends EntityImpl implements Comment {

    public function __construct(array $commentSpec) {
        parent::__construct('comment', $commentSpec, ['id', 'text', 'content', 'users']);

        /** @var EntityDao $userDao */
        $userDao = EntityDaoFactory::createEntityDao('user');
        $this->values['user'] = $userDao->fetchExactlyOne('id', $this->getUserUuid());

        /** @var CommentDao $comentDao */
        $comentDao = EntityDaoFactory::createEntityDao('comment');
        $this->values['children'] = $comentDao->getByParent($this->getUuid());
    }

    function getTitle(): string {
        return $this->getAttributeValue('title', false);
    }

    function getCommentText(): string {
        return $this->getAttributeValue('text');
    }

    function isQuestion(): bool {
        try {
            return $this->getAttributeValue('is_question');
        } catch(\Exception $e) {
            return false;
        }
    }

    function getParentUuid(): string {
        return $this->getAttributeValue('parent', false);
    }

    function getContentUuid(): string {
        return $this->getAttributeValue('content');
    }

    function getUserUuid(): string {
        return $this->getAttributeValue('users');
    }

    public function setRecentReply($reply) {
        $this->values['recent_reply'] = $reply;
    }

}