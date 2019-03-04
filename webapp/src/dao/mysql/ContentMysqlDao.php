<?php


namespace msse661\dao\mysql;


use msse661\Content;
use msse661\ContentImpl;
use msse661\dao\ContentDao;
use msse661\TagImpl;

class ContentMysqlDao extends BaseMysqlDao implements ContentDao {

    public function __construct() {
        parent::__construct('content_view', '\\msse661\\ContentImpl', 'created DESC');
    }

    public function create(array $contentSpec): Content {
        $optDescription = isset($contentSpec['description']) ? "':description'" : 'NULL';
        $query  = <<<________QUERY
            INSERT INTO content (id, title, description, users, state, path, mime_type, hash)
            VALUES (':id',
                    ':title',
                    {$optDescription},
                    ':users', 
                    (SELECT id FROM content_state WHERE name = ':state' LIMIT 1),
                    ':path',
                    ':mime_type',
                    ':hash')
________QUERY;

        $contentSpec = $this->createEntity($contentSpec, $query);

        return new ContentImpl($contentSpec);
    }

    public function getAll(int $offset = 0, int $limit = 0): array {
        return $this->fetch($offset, $limit);
    }

    public function getByUuid(string $uuid): Content {
        return $this->fetchExactlyOne('id', $uuid);
    }

    public function hashExists(string $hash): bool {
        return $this->countWhere("hash = ':hash'", ['hash' => $hash]) > 0;
    }

    public function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array {
        return $this->fetchWhere("title LIKE '%:title%'", ['title' => $title], $offset, $limit);
    }
}