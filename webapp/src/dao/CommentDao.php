<?php


namespace msse661\dao;


use msse661\Comment;

interface CommentDao {

    function create(array $commentSpec): Comment;

    function getByUuid(string $uuid): Comment;

    function countByContent(string $contentUuid): int;

    function getByContent(string $contentUuid, int $offset = 0, int $limit = 0): array;

    function getByParent(string $commentUuid, int $offset = 0, int $limit = 0): array;

    function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array;

}