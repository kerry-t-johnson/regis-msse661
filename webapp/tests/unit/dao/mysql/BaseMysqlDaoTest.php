<?php

namespace msse661\dao\mysql;

use msse661\BaseTestCase;
use msse661\Config;

class BaseMysqlDaoTest extends BaseTestCase {

    protected $tables;

    public function __construct(array $tablesToClean = []) {
        parent::__construct();

        $this->tables = $tablesToClean;
    }

    protected function tearDown() {
        parent::tearDown();

        $databaseConfig = Config::getDatabaseConfig();

        $dao = new \mysqli($databaseConfig['host'], $databaseConfig['user'], $databaseConfig['password'], $databaseConfig['database']);
        foreach($this->tables as $table) {
            $dao->query("DELETE FROM {$table} WHERE id LIKE '%-test-%'");
        }
    }

}
