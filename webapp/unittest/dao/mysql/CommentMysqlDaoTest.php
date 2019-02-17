<?php

namespace msse661\dao\mysql;

use msse661\Comment;

class CommentMysqlDaoTest extends BaseMysqlDaoTest {

    /** @var CommentMysqlDao */
    private $uut;

    public function __construct() {
        parent::__construct(['comments', 'content', 'users']);

        $this->uut = new CommentMysqlDao();
    }

    public function testCreate() {
        $testComment    = self::createTestComment();

        $this->assertCommentExists($testComment);
    }

    public function testGetByUuid() {
        $expectedComment    = self::createTestComment();
        $actualComment      = $this->uut->getByUuid($expectedComment->getUuid());

        $this->assertCommentEquals($expectedComment, $actualComment);
    }

    public function testGetByContent() {
        $expectedComment    = self::createTestComment();
        $actualComment      = $this->uut->getByContent($expectedComment->getContentUuid());

        $this->assertEquals(1, count($actualComment));
        $this->assertCommentEquals($expectedComment, $actualComment[0]);

    }

    private function assertCommentExists(Comment $comment) {
        $query = <<<________QUERY
          SELECT  * 
          FROM    comments
          WHERE   id        = ':id'
          AND     text      = ':text'
          AND     content   = ':content' 
          AND     users     = ':users'
________QUERY;
        $query = $this->uut->escapeQuery($query,
            ['id'       => $comment->getUuid(),
             'text'     => $comment->getCommentText(),
             'content'  => $comment->getContentUuid(),
             'users'    => $comment->getUserUuid(),
            ]);

        $result = $this->uut->query($query);

        $this->assertEquals(1, $result->num_rows);
    }

    private function assertCommentEquals(Comment $expected, Comment $actual) {
        $this->assertEquals($expected->getUuid(), $actual->getUuid());
        $this->assertEquals($expected->getCommentText(), $actual->getCommentText());
        $this->assertEquals($expected->getContentUuid(), $actual->getContentUuid());
        $this->assertEquals($expected->getUserUuid(), $actual->getUserUuid());
    }

    public static function createTestComment(): Comment {
        $dao        = new CommentMysqlDao();
        $content    = ContentMysqlDaoTest::createTestContent();
        $user       = UserMysqlDaoTest::createTestUser();

        $testCommentSpec = [
            'title'         => self::generateRandomString(),
            'text'          => self::generateRandomString(),
            'is_question'   => false,
            'content'       => $content->getUuid(),
            'users'         => $user->getUuid(),
        ];

        return $dao->create($testCommentSpec);
    }
}
