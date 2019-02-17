<?php


namespace msse661;


class CommentImpl extends EntityImpl implements Comment {

    public function __construct(array $commentSpec) {
        parent::__construct($commentSpec, ['id', 'text', 'content', 'users']);
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


}