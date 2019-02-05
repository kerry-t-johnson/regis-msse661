<?php


namespace msse661\dao\mysql;


use msse661\dao\TagDao;
use msse661\Tag;
use msse661\TagImpl;

class TagMysqlDao extends BaseMysqlDao implements TagDao {

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

    function getByUuid(string $uuid): Tag {
        return new TagImpl($this->fetchExactlyOne('tag', 'id', $uuid));
    }

    function getByName(string $name): Tag {
        return new TagImpl($this->fetchExactlyOne('tag', 'name', $name));
    }


}