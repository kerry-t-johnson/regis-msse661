<?php


namespace msse661\dao\mysql;


use msse661\Comment;
use msse661\CommentImpl;
use msse661\dao\CommentDao;

class CommentMysqlDao extends BaseMysqlDao implements CommentDao {

    public function __construct() {
        parent::__construct('comments', '\\msse661\\CommentImpl', 'created DESC');
    }

    public function create(array $commentSpec): Comment {
        $optTitle   = isset($commentSpec['title']) ? ':title' : 'NULL';
        $optParent  = isset($commentSpec['parent']) ? ':parent' : 'NULL';
        $optQues    = isset($commentSpec['is_question']) && $commentSpec['is_question'] ? '1' : '0';

        $query  = <<<________QUERY
            INSERT INTO comments
                        (id,    title,        text,     is_question,   parent,        content,    users)
            VALUES      (:id, {$optTitle},  :text,  {$optQues},    {$optParent},  :content, :users)
________QUERY;

        $commentSpec = $this->createEntity($commentSpec, $query);

        return new CommentImpl($commentSpec);
    }

    public function countByContent(string $contentUuid): int {
        return $this->countWhere('content = :content', ['content' => $contentUuid]);
    }

    public function getByUuid(string $uuid): Comment {
        return $this->fetchExactlyOne('id', $uuid);
    }

    public function getByContent(string $contentUuid, int $offset = 0, int $limit = 0): array {
        return $this->fetchWhere("content = :content AND parent IS NULL", ['content' => $contentUuid], $offset, $limit, 'created DESC');
    }

    public function getByParent(string $commentUuid, int $offset = 0, int $limit = 0): array {
        return $this->fetchWhere("parent = :parent", ['parent' => $commentUuid], $offset, $limit, 'created DESC');
    }

    public function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array {
        return $this->fetchWhere("title LIKE :title", ['title' => "%{$title}%"], $offset, $limit);
    }
}