<?php


namespace msse661;


class NoSuchControllerException extends PianoException {

    public function __construct(?string $entity_type) {
        parent::__construct("No controller for entity type: {$entity_type}", 500);
    }

}