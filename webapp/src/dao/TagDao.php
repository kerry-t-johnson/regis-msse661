<?php


namespace msse661\dao;


use msse661\Tag;

interface TagDao {

    function create(array $tagSpec): Tag;

    function getByUuid(string $uuid): Tag;

    function getByName(string $name): Tag;

    function applyTagsToContent(string $content_uuid, $tag, bool $clearOthers = false);

    function removeTagsFromContent(string $content_uuid, $tag);

    function getTagsByContent(string $content_uuid): array;

    function getTagsByUser(string $user_uuid): array;

    function saveUserTags(string $user_uuid, $tag);

    function clearUserTags(string $user_uuid, $tag);
}