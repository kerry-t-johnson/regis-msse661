<?php

namespace msse661\dao\mysql;

use msse661\BaseTestCase;
use msse661\User;

class UserMysqlDaoTest extends BaseMysqlDaoTest {

    /** @var UserMysqlDao */
    private $uut;

    public function __construct() {
        parent::__construct(['users']);

        $this->uut = new UserMysqlDao();
    }

    public static function createTestUser(): User {
        $first_name = self::generateRandomName(5);
        $last_name = self::generateRandomName(7);

        $testUserSpec = [
            'email' => self::generateRandomEmail("{$first_name}.{$last_name}"),
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];

        $uut = new UserMysqlDao();
        return $uut->create($testUserSpec);
    }

    public function testCreate() {
        $testUser = self::createTestUser();
        $this->assertUserExists($testUser);
    }

    public function testGetByUuid(): void {
        $expectedUser   = self::createTestUser();
        $actualUser     = $this->uut->getByUuid($expectedUser->getUuid());

        $this->assertUserEquals($expectedUser, $actualUser);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetByEmailDoesntExist(): void {
        $first_name = self::generateRandomName(5);
        $last_name = self::generateRandomName(7);
        $email = self::generateRandomEmail("{$first_name}.{$last_name}");

        $this->uut->getByEmail($email);
    }

    public function testGetByEmail(): void {
        $expectedUser   = self::createTestUser();
        $actualUser     = $this->uut->getByEmail($expectedUser->getEmail());

        $this->assertUserEquals($expectedUser, $actualUser);
    }

    private function assertUserExists(User $user) {
        $query = "SELECT * FROM users WHERE id = ':id' AND email = ':email' AND first_name = ':first_name' AND last_name = ':last_name'";
        $query = $this->uut->escapeQuery($query,
                                        [   'id'            => $user->getUuid(),
                                            'email'         => $user->getEmail(),
                                            'first_name'    => $user->getFirstName(),
                                            'last_name'     => $user->getLastName(),
                                        ]);

        $result = $this->uut->query($query);

        $this->assertEquals(1, $result->num_rows);
    }

    private function assertUserEquals(User $expected, User $actual) {
        $this->assertEquals($expected->getUuid(), $actual->getUuid());
        $this->assertEquals($expected->getFirstName(), $actual->getFirstName());
        $this->assertEquals($expected->getLastName(), $actual->getLastName());
        $this->assertEquals($expected->getEmail(), $actual->getEmail());
    }
}
