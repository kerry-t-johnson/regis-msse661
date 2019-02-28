<?php


namespace msse661;


interface Entity {

    public function getUuid(): string;

    public function getCreationDateTime(): \DateTime;

    public function getUpdatedDateTime(): \DateTime;


}