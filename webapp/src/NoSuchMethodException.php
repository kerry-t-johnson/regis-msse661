<?php


namespace msse661;


class NoSuchMethodException extends PianoException {

    public function __construct(object $object, string $method_name) {
        $type = get_class($object);
        parent::__construct("No such method: {$type}::{$method_name}", 500);
    }
}