<?php


namespace msse661;


interface Comment extends Entity {

    function getTitle(): string;

    function getCommentText(): string;

    function isQuestion(): bool;

    function getParentUuid(): string;

    function getContentUuid(): string;

    function getUserUuid(): string;

}