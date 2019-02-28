<?php


namespace msse661;


use Monolog\Logger;
use msse661\util\logger\LoggerManager;

class EntityImpl implements \JsonSerializable
{
    private $entity_type;
    private $values;
    private $hiddenValues;

    /** @var Logger */
    protected $logger;

    protected function __construct(string $entity_type, array $spec, array $requiredKeys, array $hiddenValues = []) {
        $this->assertRequiredSpec($spec, $requiredKeys);

        $this->entity_type  = $entity_type;
        $this->values       = $spec;
        $this->hiddenValues = $hiddenValues;

        $this->logger = LoggerManager::getLogger(get_class($this));
    }

    public function getUuid(): string {
        return $this->getAttributeValue('id');
    }

    public function getCreationDateTime(): \DateTime {
        return new \DateTime($this->getAttributeValue('created'));
    }

    public function getUpdatedDateTime(): \DateTime {
        return new \sDateTime($this->getAttributeValue('updated'));
    }

    public function jsonSerialize() {
        $json_values    = $this->values;
        foreach($this->hiddenValues as $h) {
            unset($json_values[$h]);
        }
        return $json_values;
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