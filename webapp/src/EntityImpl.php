<?php


namespace msse661;


class EntityImpl
{
    private $values;

    protected function __construct(array $spec, array $requiredKeys) {
        $this->assertRequiredSpec($spec, $requiredKeys);
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
            $this->assertValueExists($this->values, $attributeName);
        }

        return $this->values[$attributeName] ?? null;
    }

    protected function assertValueExists(array $values, string $key) {
        if(!array_key_exists($key, $values)) {
            throw new \Exception(get_class($this) . " has no attribute: {$key}");
        }
    }

    private function assertRequiredSpec($values, $requiredKeys) {
        foreach($requiredKeys as $key) {
            $this->assertValueExists($values, $key);
        }
    }

}