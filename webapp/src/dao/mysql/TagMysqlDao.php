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
        $optDescription = isset($tagSpec['description']) ? "':description'" : 'NULL';
        $optParent      = isset($tagSpec['parent']) ? "':parent'" : 'NULL';

        $query      = <<<________QUERY
            INSERT INTO tag
                        (id,    name,     description,        parent)
            VALUES      (':id', ':name',  {$optDescription},   {$optParent})
________QUERY;

        $tagSpec = $this->createEntity($tagSpec, $query);

        return new TagImpl($tagSpec);
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

    public function applyTagsToContent(string $content_uuid, $tag) {
        $tags = is_array($tag) ? $tag : [ $tag ];

        foreach($tags as $t) {
            $query = <<<____________QUERY
                INSERT IGNORE INTO  content_tag
                                    (content_id,    tag_id)
                VALUES              (':content_id', ':tag_id')
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
            WHERE     content_tag.content_id = ':content_id'
            AND       content_tag.tag_id = tag.id
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

}