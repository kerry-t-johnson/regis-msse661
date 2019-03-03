<?php


namespace msse661\dao;


use msse661\Tag;

interface TagDao {

    function create(array $tagSpec): Tag;

    function getByUuid(string $uuid): Tag;

    function getByName(string $name): Tag;

    function applyTagsToContent(string $content_uuid, $tag);

    function getTagsByContent(string $content_uuid): array;
}