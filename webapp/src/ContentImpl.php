<?php


namespace msse661;


class ContentImpl extends EntityImpl implements Content {

    private const REQUIRED_KEYS = ['id', 'title', 'users', 'state', 'path', 'hash'];

    public function __construct(array $contentSpec) {
        parent::__construct($contentSpec, self::REQUIRED_KEYS);
    }

    public function getTitle(): string {
        return $this->getAttributeValue('title');
    }

    public function getDescription(): string {
        return $this->getAttributeValue('description', false) ?? '';
    }

    public function getUserUuid(): string {
        return $this->getAttributeValue('users');
    }

    public function getState(): string {
        return $this->getAttributeValue('state');
    }

    public function getPath(): string {
        return $this->getAttributeValue('path');
    }

    public function getContent(): string {
        return file_get_contents('http://loripsum.net/api/' . $this->getPath());
    }

    public function getHash(): string {
        return $this->getAttributeValue('hash');
    }

    public function getMimeType(): string {
        $fileInfo = new \finfo();
        return $fileInfo->file($this->getFullPath(), FILEINFO_MIME_TYPE);
    }

    public function getImageType(): int {
        return exif_imagetype($this->getFullPath());
    }

    public function getFullPath(): string {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->getPath();
    }
}