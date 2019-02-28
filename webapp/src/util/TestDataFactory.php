<?php

namespace {
define('APP_TEST_ENV', 'APP_TEST_ENV');
}

namespace msse661\util {


    use msse661\dao\mysql\TagMysqlDao;

    class TestDataFactory {

        public function __construct() {
            // Load the schema:
            new \msse661\dao\mysql\MysqlDatabase(new \msse661\dao\Schema());
        }

        public function createTestData(string $testDataFile) : void {
            $data = json_decode(file_get_contents($testDataFile), TRUE);

            $this->_createTestData($data);
        }

        private function _createTestData($data) {
            $test_users     = $data['test_users'] ?? [];
            $test_tags      = $data['test_tags'] ?? [];
            $test_content   = $data['test_content'] ?? [];
            $test_comments  = $data['test_comments'] ?? [];

            $actual_users = [];
            foreach ($test_users as $testId => &$userSpec) {
                $actual_users[$testId] = $this->createOrRetrieveTestUser($userSpec);
            }

            $actual_tags = [];
            foreach($test_tags as $testId => &$tagSpec) {
                $actual_tags[$testId] = $this->createOrRetrieveTestTag($tagSpec);
            }

            $actual_content = [];
            foreach ($test_content as $testId => &$contentSpec) {
                /** @var $user \msse661\User */
                $user = $actual_users[$contentSpec['test_user_id']];

                $contentSpec['users'] = $user->getUuid();
                $contentSpec['hash'] = sha1($contentSpec['title']);

                $actual_content[$testId] = $this->createOrRetrieveTestContent($contentSpec);
            }

            $actual_comments = [];
            foreach ($test_comments as $testId => &$commentSpec) {
                /** @var $content \msse661\Content */
                $content = $actual_content[$commentSpec['test_content_id']];
                /** @var $user \msse661\User */
                $user = $actual_users[$commentSpec['test_user_id']];

                $commentSpec['content'] = $content->getUuid();
                $commentSpec['users'] = $user->getUuid();

                $actual_comments[$testId] = $this->createOrRetrieveTestComments($commentSpec);
            }
        }

        private function createOrRetrieveTestUser($userSpec): \msse661\User {
            $userDao = new \msse661\dao\mysql\UserMysqlDao();

            try {
                return $userDao->getByEmail($userSpec['email']);
            }
            catch (\Exception $e) {
                return $userDao->create($userSpec);
            }
        }

        private function createOrRetrieveTestTag($tagSpec): \msse661\User {
            $tagDao = new TagMysqlDao();

            try {
                return $tagDao->getByName($tagSpec['name']);
            }
            catch (\Exception $e) {
                return $tagDao->create($tagSpec);
            }
        }

        private function createOrRetrieveTestContent($contentSpec): \msse661\Content {
            $contentDao = new \msse661\dao\mysql\ContentMysqlDao();

            $content = $contentDao->getByTitleLike($contentSpec['title']);

            if (count($content) > 0) {
                return $content[0];
            }
            else {
                return $contentDao->create($contentSpec);
            }
        }

        private function createOrRetrieveTestComments($commentSpec): \msse661\Comment {
            $commentDao = new \msse661\dao\mysql\CommentMysqlDao();

            $comment = $commentDao->getByTitleLike($commentSpec['title']);

            if (count($comment)) {
                return $comment[0];
            }
            else {
                return $commentDao->create($commentSpec);
            }
        }

    }
}