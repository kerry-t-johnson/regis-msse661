<?php


namespace msse661\dao\mysql;


use msse661\dao\TagDao;
use msse661\Tag;
use msse661\TagImpl;

class TagMysqlDao extends BaseMysqlDao implements TagDao {

    public function __construct() {
        parent::__construct('tag', '\\msse661\\TagImpl', 'name ASC');
    }

    function create(array $tagSpec): Tag {
        $optDescription = isset($tagSpec['description']) ? ':description' : 'NULL';
        $optParent      = isset($tagSpec['parent']) ? ':parent' : 'NULL';

        $query      = <<<________QUERY
            INSERT INTO tag
                        (id,    name,     description,        parent)
            VALUES      (:id,   :name,    {$optDescription},   {$optParent})
________QUERY;

        return $this->createEntity($tagSpec, $query);
    }

    public function getRandom(int $limit = 5) {
        return $this->fetch(0, $limit, 'RAND()');
    }

    public function getByUuid(string $uuid): Tag {
        return $this->fetchExactlyOne('id', $uuid);
    }

    public function getByName(string $name): Tag {
        return $this->fetchExactlyOne('name', $name);
    }

    public function applyTagsToContent(string $content_uuid, $tag, bool $clearOthers = false) {
        $tags = is_array($tag) ? $tag : [ $tag ];

        if($clearOthers) {
            $clearQuery = <<<____________QUERY
                DELETE
                FROM    content_tag
                WHERE   content_id = :content_id
                AND     tag_id NOT IN (:tags)
____________QUERY;

            $clearQuery = $this->escapeQuery($clearQuery, ['content_id' => $content_uuid, 'tags' => $tags]);
            $this->query($clearQuery);
        }

        foreach($tags as $t) {
            $query = <<<____________QUERY
                INSERT IGNORE INTO  content_tag
                                    (content_id,    tag_id)
                VALUES              (:content_id,   :tag_id)
____________QUERY;

            $query  = $this->escapeQuery($query, ['content_id' => $content_uuid, 'tag_id' => $t]);
            $this->query($query);
        }
    }

    function removeTagsFromContent(string $content_uuid, $tag) {
        $tags = is_array($tag) ? $tag : [ $tag ];

        foreach($tags as $t) {
            $query = <<<____________QUERY
                DELETE FROM content_tag
                WHERE       content_id = :content_id
                AND         tag_id     = :tag_id
____________QUERY;

            $query  = $this->escapeQuery($query, ['content_id' => $content_uuid, 'tag_id' => $t]);
            $this->query($query);
        }
    }

    public function getTagsByContent(string $content_uuid): array {
        $query  = <<<________QUERY
            SELECT    tag.*
            FROM      tag,
                      content_tag
            WHERE     content_tag.content_id = :content_id
            AND       content_tag.tag_id     = tag.id
            ORDER BY  tag.name
________QUERY;

        $query  = $this->escapeQuery($query, ['content_id' => $content_uuid]);
        $result = $this->query($query);

        $ret = [];
        while($row = $result->fetch_assoc()) {
            $ret[] = new TagImpl($row);
        }

        return $ret;
    }

    public function saveUserTags(string $user_uuid, $tag) {
        $tags = is_array($tag) ? $tag : [ $tag ];

        foreach($tags as $t) {
            $query = <<<____________QUERY
                INSERT IGNORE INTO  user_tag
                                    (user_id,    tag_id)
                VALUES              (:user_id,   :tag_id)
____________QUERY;

            $query  = $this->escapeQuery($query, ['user_id' => $user_uuid, 'tag_id' => $t]);
            $this->logger->debug('saveUserTags', ['query' => $query]);
            $this->query($query);
        }
    }

    function clearUserTags(string $user_uuid, $tag) {
        $tags = is_array($tag) ? $tag : [ $tag ];

        foreach($tags as $t) {
            $query = <<<____________QUERY
                DELETE FROM user_tag
                WHERE       user_id = :user_id
                AND         tag_id  = :tag_id
____________QUERY;

            $query  = $this->escapeQuery($query, ['user_id' => $user_uuid, 'tag_id' => $t]);
            $this->query($query);
        }
    }

    public function getTagsByUser(string $user_uuid, bool $assoc = false): array {
        $query  = <<<________QUERY
            SELECT    tag.*
            FROM      tag,
                      user_tag
            WHERE     user_tag.user_id = :user_id
            AND       user_tag.tag_id  = tag.id
            ORDER BY  tag.name
________QUERY;

        $query  = $this->escapeQuery($query, ['user_id' => $user_uuid]);
        $this->logger->debug('getTagsByUser', ['query' => $query]);
        $result = $this->query($query);

        $ret = [];
        while($row = $result->fetch_assoc()) {
            $tag    = new TagImpl($row);

            if($assoc) {
                $ret[$tag->getUuid()] = $tag;
            }
            else {
                $ret[] = $tag;
            }
        }

        return $ret;
    }

}