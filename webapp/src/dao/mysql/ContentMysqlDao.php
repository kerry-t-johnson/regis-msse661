<?php


namespace msse661\dao\mysql;


use msse661\Content;
use msse661\ContentImpl;
use msse661\dao\ContentDao;

class ContentMysqlDao extends BaseMysqlDao implements ContentDao {

    public function create(array $contentSpec): Content {
        $query  = <<<________QUERY
            INSERT INTO content (id, title, users, state, path)
            VALUES (':id',
                    ':title',
                    ':users', 
                    (SELECT id FROM content_state WHERE name = ':state' LIMIT 1),
                   ':path')
________QUERY;

        $contentSpec = $this->createEntity($contentSpec, $query);

        return new ContentImpl($contentSpec);
    }

    public function getAll(int $offset = 0, int $limit = 0) {
        $raw = $this->fetch('content', $offset, $limit);

        $entities = [];
        foreach($raw as $r) {
            $entities[] = new ContentImpl($r);
        }
        return $entities;
    }

    public function getByUuid(string $uuid): ?Content {
        return new ContentImpl($this->fetchExactlyOne('content', 'id', $uuid));
    }

    public function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array {
        $raw = $this->fetchWhere('content', "title LIKE '%:title%'", ['title' => $title], $offset, $limit);

        $entities = [];
        foreach($raw as $r) {
            $entities[] = new ContentImpl($r);
        }
        return $entities;
    }
}