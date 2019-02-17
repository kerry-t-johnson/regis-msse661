<?php


namespace msse661\dao\mysql;


use msse661\Comment;
use msse661\CommentImpl;
use msse661\dao\CommentDao;

class CommentMysqlDao extends BaseMysqlDao implements CommentDao {

    public function create(array $commentSpec): Comment {
        $optTitle   = isset($commentSpec['title']) ? "':title'" : 'NULL';
        $optParent  = isset($commentSpec['parent']) ? "':parent'" : 'NULL';
        $optQues    = isset($commentSpec['is_question']) && $commentSpec['is_question'] ? '1' : '0';

        $query  = <<<________QUERY
            INSERT INTO comments
                        (id,    title,        text,     is_question,   parent,        content,    users)
            VALUES      (':id', {$optTitle},  ':text',  {$optQues},    {$optParent},  ':content', ':users')
________QUERY;

        $commentSpec = $this->createEntity($commentSpec, $query);

        return new CommentImpl($commentSpec);
    }

    public function getByUuid(string $uuid): Comment {
        return new CommentImpl($this->fetchExactlyOne('comments', 'id', $uuid));
    }

    public function getByContent(string $contentUuid, int $offset = 0, int $limit = 0): array {
        $raw = $this->fetchWhere('comments', "content = ':content'", ['content' => $contentUuid], $offset, $limit);

        $entities = [];
        foreach($raw as $r) {
            $entities[] = new CommentImpl($r);
        }
        return $entities;
    }

    public function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array {
        $raw = $this->fetchWhere('comment', "title LIKE '%:title%'", ['title' => $title], $offset, $limit);

        $entities = [];
        foreach($raw as $r) {
            $entities[] = new CommentImpl($r);
        }
        return $entities;
    }
}