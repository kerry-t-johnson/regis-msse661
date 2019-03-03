<?php


namespace msse661;


interface Content extends Entity {

    function getTitle(): string;

    function getDescription(): string;

    function getUserUuid(): string;

    function getUser(): User;

    function getPath(): string;

    function getState(): string;

    function getHash(): string;

    function getMimeType(): string;

    function getImageType(): int;

    function getTags(): array;

}