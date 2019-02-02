<?php

namespace msse661\dao\mysql;

use Monolog\Logger;
use msse661\BaseTestCase;
use msse661\User;
use msse661\util\logger\LoggerManager;

class UserMysqlDaoTest extends BaseTestCase {

    /** @var Logger */
    private $logger;

    public function testCreate() {
        $this->logger = LoggerManager::getLogger(basename(__FILE__, '.php'));

        $first_name = self::generateRandomName(5);
        $last_name = self::generateRandomName(7);

        $testUserSpec = [
            'email' => self::generateRandomEmail("{$first_name}.{$last_name}"),
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];

        $uut = new UserMysqlDao();
        $testUser = $uut->create($testUserSpec);

        $this->assertUserExists($testUser);

    }

    public function testGetByUuid(): void {
        $first_name = self::generateRandomName(5);
        $last_name = self::generateRandomName(7);

        $testUserSpec = [
            'email' => self::generateRandomEmail("{$first_name}.{$last_name}"),
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];

        $uut = new UserMysqlDao();
        $testUser = $uut->create($testUserSpec);

        $actualUser = $uut->getByUuid($testUser->getUuid());

        $this->assertUserEquals($testUser, $actualUser);
    }

    public function testGetByEmailDoesntExist(): void {
        $first_name = self::generateRandomName(5);
        $last_name = self::generateRandomName(7);
        $email = self::generateRandomEmail("{$first_name}.{$last_name}");

        $uut = new UserMysqlDao();
        $actualUser = $uut->getByEmail($email);

        $this->assertNull($actualUser);
    }

    public function testGetByEmail(): void {
        $first_name = self::generateRandomName(5);
        $last_name = self::generateRandomName(7);

        $testUserSpec = [
            'email' => self::generateRandomEmail("{$first_name}.{$last_name}"),
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];

        $uut = new UserMysqlDao();
        $testUser = $uut->create($testUserSpec);

        $actualUser = $uut->getByEmail($testUserSpec['email']);

        $this->assertUserEquals($testUser, $actualUser);
    }

    private function assertUserExists(User $user) {
        $uut = new UserMysqlDao();

        $this->logger->debug('assertUserExists', ['user' => $user]);

        try {
            $query = "SELECT * FROM users WHERE id = ':id' AND email = ':email' AND first_name = ':first_name' AND last_name = ':last_name'";
            $query = $uut->escapeQuery($query, [
                                        'id'            => $user->getUuid(),
                                        'email'         => $user->getEmail(),
                                        'first_name'    => $user->getFirstName(),
                                        'last_name'     => $user->getLastName(),
                                        ]);

            $result = $uut->query($query);

            $this->assertEquals(1, $result->num_rows);
        }
        finally {
            $query = "DELETE FROM users WHERE id = ':id'";
            $query = $uut->escapeQuery($query, ['id' => $user->getUuid()]);

            $uut->query($query);
        }
    }

    private function assertUserEquals(User $expected, User $actual) {
        $this->assertEquals($expected->getUuid(), $actual->getUuid());
        $this->assertEquals($expected->getFirstName(), $actual->getFirstName());
        $this->assertEquals($expected->getLastName(), $actual->getLastName());
        $this->assertEquals($expected->getEmail(), $actual->getEmail());
    }
}
