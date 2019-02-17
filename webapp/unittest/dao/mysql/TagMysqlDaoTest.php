<?php

namespace msse661\dao\mysql;

use msse661\Tag;

class TagMysqlDaoTest extends BaseMysqlDaoTest {

    /** @var TagMysqlDao */
    private $uut;

    public function __construct() {
        parent::__construct(['tag']);

        $this->uut = new TagMysqlDao();
    }

    public function testCreate() {
        $testTag = self::createTestTag();

        $this->assertTagExists($testTag);
    }

    public function testGetByUuid() {
        $expectedTag    = self::createTestTag();
        $actualTag      = $this->uut->getByUuid($expectedTag->getUuid());

        $this->assertTagEquals($expectedTag, $actualTag);
    }

    public function testGetByName() {
        $expectedTag    = self::createTestTag();
        $actualTag      = $this->uut->getByName($expectedTag->getName());

        $this->assertTagEquals($expectedTag, $actualTag);
    }

    private function assertTagExists(Tag $tag) {
        $query = <<<________QUERY
          SELECT  * 
          FROM    tag
          WHERE   id          = ':id'
          AND     name        = ':name'
          AND     description = ':description'
________QUERY;
        $query = $this->uut->escapeQuery($query,
            ['id'           => $tag->getUuid(),
             'name'         => $tag->getName(),
             'description'  => $tag->getDescription(),
            ]);

        $result = $this->uut->query($query);

        $this->assertEquals(1, $result->num_rows);
    }

    private function assertTagEquals(Tag $expected, Tag $actual) {
        $this->assertEquals($expected->getUuid(), $actual->getUuid());
        $this->assertEquals($expected->getName(), $actual->getName());
        $this->assertEquals($expected->getDescription(), $actual->getDescription());
    }

    public static function createTestTag(): Tag {
        $dao = new TagMysqlDao();

        $testTagSpec = [
            'name'          => self::generateRandomName(5),
            'description'   => self::generateRandomString(50),
        ];

        return $dao->create($testTagSpec);
    }
}
