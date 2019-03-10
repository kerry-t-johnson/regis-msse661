<?php


namespace msse661\dao;


use msse661\Entity;

interface EntityDao {

    function fetchExactlyOne(string $key, string $value): ?Entity;

    function fetchWhere(string $where, array $values, int $offset = 0, int $limit = 0, string $orderBy = '', array $join_tables = []): array;

    function fetch(int $offset = 0, int $limit = 0): array;

}