<?php

namespace msse661\dao\mysql;

use msse661\Content;

class ContentMysqlDaoTest extends BaseMysqlDaoTest {

    /** @var ContentMysqlDao */
    private $uut;

    public function __construct() {
        parent::__construct(['content', 'users']);

        $this->uut = new ContentMysqlDao();
    }

    public function testCreate() {
        $testContent = self::createTestContent();

        $this->assertContentExists($testContent);
    }

    public function testGetByUuid() {
        $expectedContent    = self::createTestContent();
        $actualContent      = $this->uut->getByUuid($expectedContent->getUuid());

        $this->assertContentEquals($expectedContent, $actualContent);
    }

    private function assertContentExists(Content $content) {
        $query = <<<________QUERY
          SELECT  * 
          FROM    content
          WHERE   id    = ':id'
          AND     users = ':users'
          AND     state = (SELECT id FROM content_state WHERE name = ':state' LIMIT 1) 
          AND     path  = ':path'
________QUERY;
        $query = $this->uut->escapeQuery($query,
            [   'id'    => $content->getUuid(),
                'users' => $content->getUserUuid(),
                'state' => $content->getState(),
                'path'  => $content->getPath(),
            ]);

        $result = $this->uut->query($query);

        $this->assertEquals(1, $result->num_rows);
    }

    private function assertContentEquals(Content $expected, Content $actual) {
        $this->assertEquals($expected->getUuid(), $actual->getUuid());
        $this->assertEquals($expected->getUserUuid(), $actual->getUserUuid());
        $this->assertEquals($expected->getPath(), $actual->getPath());
    }

    public static function createTestContent(): Content {
        $dao = new ContentMysqlDao();
        $user = UserMysqlDaoTest::createTestUser();

        $testContentSpec = [
            'title' => self::generateRandomString(10),
            'users' => $user->getUuid(),
            'state' => 'pending',
            'path'  => 'https://loripsum.net/api/5/medium/headers/code',
            'hash'  => sha1(self::generateRandomString(10)),
        ];

        return $dao->create($testContentSpec);
    }
}
