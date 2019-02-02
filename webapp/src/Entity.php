<?php


namespace msse661;


class Entity
{
    private $values;

    protected function __construct($spec) {
        $this->values = $spec;
    }

    public function getUuid(): string {
        return $this->getAttributeValue('id');
    }

    public function getCreationDateTime(): \DateTime {
        return new DateTime($this->getAttributeValue('created'));
    }

    public function getUpdatedDateTime(): \DateTime {
        return new DateTime($this->getAttributeValue('updated'));
    }

    protected function getAttributeValue(string $attributeName) {
        self::assertValueExists($attributeName, $this->values);

        return $this->values[$attributeName];
    }

    protected static function assertValueExists(string $key, array $values) {
        if(!array_key_exists($key, $values)) {
            throw new \Exception("Entity has no attribute: {$key}");
        }
    }

    protected static function assertRequiredSpec($requiredKeys, $values) {
        foreach($requiredKeys as $key) {
            self::assertValueExists($key, $values);
        }
    }

}