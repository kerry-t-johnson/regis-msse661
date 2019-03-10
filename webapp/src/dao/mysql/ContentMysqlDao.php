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
        $optDescription = isset($contentSpec['description']) ? ':description' : 'NULL';
        $query  = <<<________QUERY
            INSERT INTO content (id, title, description, users, state, path, mime_type, hash)
            VALUES (:id,
                    :title,
                    {$optDescription},
                    :users, 
                    (SELECT id FROM content_state WHERE name = :state LIMIT 1),
                    :path,
                    :mime_type,
                    :hash)
________QUERY;

        $contentSpec = $this->createEntity($contentSpec, $query);

        return new ContentImpl($contentSpec);
    }

    public function update(array $contentSpec): Content {
        $query = <<<QUERY
            UPDATE  content
            SET     title       = :title,
                    description = :description
            WHERE   id    = :id
            AND     users = :users
QUERY;

        $query = $this->escapeQuery($query, $contentSpec);

        $this->query($query);

        return $this->fetchExactlyOne('id', $contentSpec['id']);
    }

    public function delete(string $contentUuid) {
        $query          = <<<________QUERY
            DELETE
            FROM    content
            WHERE   id = :content_id
________QUERY;
        $query  = $this->escapeQuery($query, ['content_id' => $contentUuid]);

        $this->query($query);
    }

    public function getAll(int $offset = 0, int $limit = 0): array {
        return $this->fetch($offset, $limit);
    }

    public function getByUuid(string $uuid): Content {
        return $this->fetchExactlyOne('id', $uuid);
    }

    public function hashExists(string $hash): bool {
        return $this->countWhere('hash = :hash', ['hash' => $hash]) > 0;
    }

    public function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array {
        return $this->fetchWhere('title LIKE :title', ['title' => "%{$title}%"], $offset, $limit);
    }

    function getByTags(array $tags, int $offset = 0, int $limit = 0): array {
        $query = <<<________QUERY
            SELECT  content.*
            FROM    content,
                    content_tag
            WHERE   content_tag.tag_id IN (:tags)
            AND     content.id = content_tag.content_id
________QUERY;

        $query = $this->escapeQuery($query, ['tags' => "'" . implode("','", $tags) . "'"]);

        $result = $this->query($query);

        $entities = [];
        while ($row = $result->fetch_assoc()) {
            $entities[] = new $this->entityClass($row);
        }
        return $entities;

    }

    public function getByUser(string $user_uuid, int $offset = 0, int $limit = 0) {
        return $this->fetchWhere('users = :user_uuid', ['user_uuid' => $user_uuid], $offset, $limit);
    }

}