<?php

namespace msse661\dao\mysql;

use msse661\BaseTestCase;
use msse661\dao\Schema;

class MysqlDatabaseTest extends BaseTestCase {

    public function testInitialize(): void {
        $databaseSchema = new Schema();

        new MysqlDatabase($databaseSchema);

        $tables = $databaseSchema->getTablesSchema();
        foreach ($tables as $tableName => $tableSpec) {
            $this->assertTableExists($tableName);
        }

        $views = $databaseSchema->getViewsSchema();
        foreach ($views as $viewName => $viewQuery) {
            $this->assertViewExists($viewName);
        }
    }

    public function testDestroy() {
        global $databaseSpec;

        $this->markTestSkipped('MysqlDatabase::destroy is not normally tested');

        $uut = new MysqlDatabase($databaseSpec);
        $uut->destroyDb();
    }

    private function assertTableExists($tableName): void {
        $helper = new BaseMysqlDao();

        $query = <<<____________QUERY_END
            SELECT  *
            FROM    information_schema.tables
            WHERE   table_schema  = '{$helper->getDatabaseName()}'
            AND     table_name    = '{$tableName}'
            LIMIT   1;
____________QUERY_END;

        $result = $helper->query($query);

        $this->assertEquals(1, mysqli_num_rows($result));
    }

    private function assertViewExists($viewName): void {
        $helper = new BaseMysqlDao();

        $query = <<<____________QUERY_END
            SELECT  *
            FROM    information_schema.views
            WHERE   table_schema  = '{$helper->getDatabaseName()}'
            AND     table_name    = '{$viewName}'
            LIMIT   1;
____________QUERY_END;

        $result = $helper->query($query);

        $this->assertEquals(1, mysqli_num_rows($result));
    }

}
