<?php


namespace msse661\dao\mysql;

use msse661\dao\Schema;
use msse661\util\logger\LoggerManager;

class MysqlDatabase extends \mysqli
{
    /** @var Schema */
    private $dbSpec;

    /** @var \Monolog\Logger  */
    protected $logger;

    public function __construct($dbSpec) {
        $this->database_host    = $_ENV['MYSQL_HOST'] ?? 'localhost';
        $this->database_name    = $_ENV['MYSQL_DATABASE'] ?? 'regis';

        parent::__construct(
            $this->database_host,
            $_ENV['MYSQL_USER'] ?? 'regis',
            $_ENV['MYSQL_PASSWORD'] ?? 'regis123',
            $this->database_name);

        $this->dbSpec = $dbSpec;
        $this->logger = LoggerManager::getLogger('MysqlDatabase');

        $this->initialize();
    }

    public function initialize($dbSpec = null) {
        $this->dbSpec = $dbSpec ? $dbSpec : $this->dbSpec;

        $tables = $this->dbSpec->getTablesSchema();
        foreach ($tables as $tableName => $tableSpec) {
            $this->logger->info("Creating MySQL table: {$tableName}", ['spec' => $tableSpec]);

            $this->createTable($tableName, $tableSpec);
        }

        $views = $this->dbSpec->getViewsSchema();
        foreach ($views as $viewName => $viewQuery) {
            $this->logger->info("Creating MySQL view: {$tableName}");

            $this->createView($viewName, $viewQuery);
        }
    }

    public function resetDb($dbSpec = null): void {
        $this->destroyDb();
        $this->initialize($dbSpec);
    }

    public function destroyDb(): void {
        foreach ($this->dbSpec['tables'] as $tableName => $tableSpec) {
            $this->dropTable($tableName);
        }

        foreach ($this->dbSpec['views'] as $viewName => $viewQuery) {
            $this->dropView($viewName);
        }
    }

    public function createTable($tableName, $tableSpec) {
        if (array_key_exists('track-updates', $tableSpec)) {
            if ((array_key_exists('created', $tableSpec['columns']) ||
                array_key_exists('updated', $tableSpec['columns']))) {
                throw new \Exception("track-updates was specified, but column(s) 'created'/'updated' already exist in spec");
            }

            $tableSpec['columns']['created'] = [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ];

            $tableSpec['columns']['updated'] = [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
                'on-update' => 'CURRENT_TIMESTAMP',
            ];
        }

        $separator = '';
        $query = "CREATE TABLE IF NOT EXISTS {$tableName} (";

        foreach ($tableSpec['columns'] as $columnName => $columnSpec) {
            $query .= " {$separator} {$columnName} {$columnSpec['type']} ";

            if (!isset($columnSpec['null-ok']) || $columnSpec['null-ok'] === false) {
                $query .= ' NOT NULL ';
            } else {
                $query .= ' NULL ';
            }

            if (isset($columnSpec['default'])) {
                $query .= " DEFAULT {$columnSpec['default']} ";
            }

            if (isset($columnSpec['on-update'])) {
                $query .= " ON UPDATE {$columnSpec['on-update']} ";
            }

            $separator = ',';
        }

        if (isset($tableSpec['primary-key'])) {
            $query .= ", PRIMARY KEY ({$tableSpec['primary-key']}) ";
        }
        if (isset($tableSpec['foreign-keys'])) {
            foreach ($tableSpec['foreign-keys'] as $fkColumn => $fkSpec) {
                $query .= ", FOREIGN KEY ({$fkColumn}) REFERENCES {$fkSpec['reference-table']}({$fkSpec['reference-column']}) ";

                if (isset($fkSpec['on-delete-action'])) {
                    $query .= " ON DELETE {$fkSpec['on-delete-action']}";
                }
            }
        }

        if (isset($tableSpec['unique-keys'])) {
            foreach ($tableSpec['unique-keys'] as $ukName => $ukSpec) {
                $query .= ", UNIQUE KEY {$ukName} ( " . implode(', ', $ukSpec) . " ) ";
            }
        }

        $query .= ");";

        $this->logger->debug('createTable', ['query' => $query]);
        $this->query($query);

        if (isset($tableSpec['post-queries'])) {
            foreach($tableSpec['post-queries'] as $query) {
                $this->query($query);
            }
        }
    }

    public function createView($viewName, $viewQuery) {
        $query = "CREATE OR REPLACE VIEW {$viewName} AS ({$viewQuery})";
        $this->query($query);
    }

    public function dropTable($tableName): void {
        $this->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->query("DROP TABLE IF EXISTS {$tableName}");
        $this->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function dropView($viewName): void {
        $this->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->query("DROP VIEW IF EXISTS {$viewName}");
        $this->query('SET FOREIGN_KEY_CHECKS = 1');
    }

}