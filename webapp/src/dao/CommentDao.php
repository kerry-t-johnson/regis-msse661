<?php


namespace msse661\dao;


use msse661\Comment;

interface CommentDao {

    function create(array $commentSpec): Comment;

    function getByUuid(string $uuid): Comment;

    function getByContent(string $contentUuid, int $offset = 0, int $limit = 0): array;

    function getByTitleLike(string $title, int $offset = 0, int $limit = 0): array;

}