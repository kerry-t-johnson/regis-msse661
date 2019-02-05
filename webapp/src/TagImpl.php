<?php


namespace msse661;


class TagImpl extends EntityImpl implements Tag {

    public function __construct(array $tagSpec) {
        parent::__construct($tagSpec, ['id', 'name']);
    }

    function getName(): string {
        return $this->getAttributeValue('name');
    }

    function getDescription(): string {
        return $this->getAttributeValue('description');
    }

    function getParentUuid(): ?string {
        return $this->getAttributeValue('parent', false);
    }


}