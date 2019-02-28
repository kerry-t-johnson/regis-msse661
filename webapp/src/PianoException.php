<?php


namespace msse661;


class PianoException extends \Exception {

    public function __construct(string $message, int $code, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}