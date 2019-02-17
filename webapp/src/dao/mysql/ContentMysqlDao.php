<?php


namespace msse661\dao\mysql;


use msse661\Content;
use msse661\ContentImpl;
use msse661\dao\ContentDao;

class ContentMysqlDao extends BaseMysqlDao implements ContentDao {

    public function create(array $contentSpec): Content {
        $optDescription = isset($tagSpec['description']) ? "':description'" : 'NULL';
        $query  = <<<________QUERY
            INSERT INTO content (id, title, description, users, state, path, hash)
            VALUES (':id',
                    ':title',
                    {$optDescription},
                    ':users', 
                    (SELECT id FROM content_state WHERE name = ':state' LIMIT 1),
                    ':path',
                    ':hash')
________QUERY;

        $contentSpec = $this->createEntity($contentSpec, $query);

        return new ContentImpl($contentSpec);
    }

    public function getAll(int $offset = 0, int $limit = 0): array {
        $raw = $this->fetch('content', $offset, $limit);

        $entities = [];
        foreach($raw as $r) {
            $entities[] = new ContentImpl($r);
        }
        return $entities;
    }

    public function getByUuid(string $uuid): Content {
        return new ContentImpl($this->fetchExactlyOne('content', 'id', $uuid));
    }

    public function hashExists(string $hash): bool {
        return $this->countWhere('content', "hash = ':hash'", ['hash' => $hash]) > 0;
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