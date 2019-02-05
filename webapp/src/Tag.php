<?php


namespace msse661;


interface Tag extends Entity {

    function getName(): string;

    function getDescription(): string;

    function getParentUuid(): ?string;

}