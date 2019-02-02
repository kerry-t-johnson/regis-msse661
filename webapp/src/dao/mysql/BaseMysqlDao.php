<?php

namespace msse661\dao\mysql;

use msse661\util\logger\LoggerManager;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;


class BaseMysqlDao extends \mysqli
{
    protected $database_host;
    protected $database_name;

    public function __construct() {
        $this->database_host    = $_ENV['MYSQL_HOST'] ?? 'mysql';
        $this->database_name    = $_ENV['MYSQL_DATABASE'] ?? 'msse661';
        /*
         * Allow the environment to override the default values.
         *
         * This allows us to have default values for the development environment,
         * but inject real values (secrets) in the production environment.
         */
        parent::__construct(
            $this->database_host,
            $_ENV['MYSQL_USER'] ?? 'msse661',
            $_ENV['MYSQL_PASSWORD'] ?? 'password',
            $this->database_name);
    }

    public function getDatabaseHost(): string {
        return $this->database_host;
    }

    public function getDatabaseName(): string {
        return $this->database_name;
    }

    public function query($query, $resultmode = MYSQLI_STORE_RESULT) {
        $result = null;
        $query  = is_string($query) ? [$query] : $query;

        foreach ($query as $q) {
            $result = parent::query($q, $resultmode);
        }

        return $result;
    }

    public function escapeQuery(string $query, array $args): string {
        $logger = LoggerManager::getLogger('test');

        $logger->debug('escapeQuery (before)', ['query' => $query, 'args' => $args]);
        foreach ($args as $key => $value) {
            $query = str_replace(":{$key}", $this->real_escape_string((string)$value), $query);
        }
        $logger->debug('escapeQuery (after)', ['query' => $query]);

        return $query;
    }

    public static function newUuid(): string {
        // This is not really a runtime error.  This indicates that the site is
        // misconfigured (composer should have downloaded Uuid and its dependencies).
        //
        // If this fails, it's appropriate to 'die'

        try {
            return Uuid::uuid4();
        } catch (UnsatisfiedDependencyException $e) {
            die('newUuid: caught exception: ' . $e->getMessage() . '\n');
        }
    }
}