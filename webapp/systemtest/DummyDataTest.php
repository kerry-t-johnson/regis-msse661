<?php

namespace {
    require_once 'test_data.php';
}

namespace msse661 {

    use PHPUnit\Framework\TestCase;

    class DummyDataTest extends TestCase {

        public function testCreateDummyData(): void {
            global $test_users;
            global $test_content;

            foreach ($test_users as $user_key => &$userSpec) {
                $user = $this->createOrRetrieveTestUser($userSpec);

                $userSpec = array_merge($userSpec, $user);
            }


        }

    }
}