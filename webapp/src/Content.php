<?php


namespace msse661;


interface Content extends Entity {

    function getTitle(): string;

    function getUserUuid(): string;

    function getPath(): string;

    function getState(): string;
}