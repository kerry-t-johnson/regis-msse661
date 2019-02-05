<?php


namespace msse661;


class EntityImpl
{
    private $values;

    protected function __construct(array $spec, array $requiredKeys) {
        self::assertRequiredSpec($spec, $requiredKeys);
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

    protected function getAttributeValue(string $attributeName, bool $required = true) {
        if ($required) {
            self::assertValueExists($this->values, $attributeName);
        }

        return $this->values[$attributeName] ?? null;
    }

    protected static function assertValueExists(array $values, string $key) {
        if(!array_key_exists($key, $values)) {
            throw new \Exception("EntityImpl has no attribute: {$key}");
        }
    }

    private static function assertRequiredSpec($values, $requiredKeys) {
        foreach($requiredKeys as $key) {
            self::assertValueExists($values, $key);
        }
    }

}