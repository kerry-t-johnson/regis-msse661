<?php


namespace msse661;


interface User
{
    public function getUuid(): string;

    public function getEmail(): string;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getCreationDateTime(): \DateTime;

    public function getUpdatedDateTime(): \DateTime;

}