<?php

namespace msse661\dao\mysql;

use msse661\BaseTestCase;

class BaseMysqlDaoTest extends BaseTestCase {

    protected $tables;

    public function __construct(array $tablesToClean = []) {
        parent::__construct();

        $this->tables = $tablesToClean;
    }

    protected function tearDown() {
        parent::tearDown();

        $dao = new BaseMysqlDao();
        foreach($this->tables as $table) {
            $dao->query("DELETE FROM {$table} WHERE id LIKE '%-test-%'");
        }
    }

}
