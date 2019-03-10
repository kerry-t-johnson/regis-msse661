<?php


namespace msse661\dao;


use msse661\Content;
use msse661\User;

interface ContentDao {

    function create(array $contentSpec): Content;

    function update(array $contentSpec): Content;

    function delete(string $contentUuid);

    function getAll(int $offset = 0, int $limit = 0): array;

    function getByUuid(string $uuid): Content;

    function hashExists(string $hash): bool;

    function getByTitleLike(string $title, int $offset = 0, int $limit = 0);

    function getByTags(array $tags, int $offset = 0, int $limit = 0);

    function getByUser(string $user_uuid, int $offset = 0, int $limit = 0);

}