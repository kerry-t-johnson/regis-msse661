<?php

namespace msse661\dao\mysql;

use msse661\util\logger\LoggerManager;
use mysql_xdevapi\Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;


class BaseMysqlDao extends \mysqli
{
    protected $database_host;
    protected $database_name;

    /** @var \Monolog\Logger  */
    protected $logger;

    public function __construct() {
        $this->database_host    = $_ENV['MYSQL_HOST'] ?? 'localhost';
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

        $this->logger = LoggerManager::getLogger(get_class($this));
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

    protected function createEntity(array $entitySpec, string $query) {
        if(array_key_exists('id', $entitySpec)) {
            throw new Exception('Entity already has UUID: ' . $entitySpec['id']);
        }

        $entitySpec['id']   = self::newUuid();
        $query              = $this->escapeQuery($query, $entitySpec);

        $this->logger->debug('createEntity', ['query' => $query]);
        if ($this->query($query) === true) {
            $this->logger->info("Created Entity", $entitySpec);
            return $entitySpec;
        } else {
            throw new \Exception('createEntity: caught exception ' . $this->error . '\n');
        }

    }

    protected function fetchExactlyOne(string $table, string $key, string $value): ?array {
        $query = "SELECT * FROM {$table} WHERE {$key} = '{$value}'";

        $result = $this->query($query);

        if($result->num_rows == 0) {
            throw new \Exception("Unable to find object to match {$key} = {$value}.");
        }
        else if($result->num_rows > 1) {
            throw new \Exception("Expected one object to match {$key} = {$value}.  Found {$result->num_rows} objects.");
        }

        return $result->fetch_assoc();
    }

    protected function fetch(string $table, int $offset = 0, int $limit = 0) {
        $limitQuery     = $limit  > 0 ? "LIMIT  {$limit}"  : '';
        $offsetQuery    = $offset > 0 ? "OFFSET {$offset}" : '';
        $query          = <<<QUERY
            SELECT  *
            FROM    {$table}
            {$limitQuery}
            {$offsetQuery}
QUERY;

        $result = $this->query($query);

        $entities = [];
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $entities[] = $row;
            }
        }
        return $entities;
    }

    protected function fetchWhere(string $table, string $where, array $values, int $offset = 0, int $limit = 0) {
        $limitQuery     = $limit  > 0 ? "LIMIT  {$limit}"  : '';
        $offsetQuery    = $offset > 0 ? "OFFSET {$offset}" : '';
        $query          = <<<QUERY
            SELECT  *
            FROM    {$table}
            WHERE   {$where}
            {$limitQuery}
            {$offsetQuery}
QUERY;
        $query  = $this->escapeQuery($query, $values);

        $result = $this->query($query);

        $entities = [];
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $entities[] = $row;
            }
        }
        return $entities;
    }

    public function escapeQuery(string $query, array $args): string {
        $logger = LoggerManager::getLogger('test');

        $logger->debug('escapeQuery (before)', ['query' => $query, 'args' => $args]);
        foreach ($args as $key => $value) {
            $query = str_replace(":{$key}", is_string($value) ? $this->real_escape_string((string)$value) : $value, $query);
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
            $uuid = Uuid::uuid4();

            if(defined(APP_TEST_ENV)) {
                return preg_replace('/(.{8})-(.{4})-/', '\1-test-', $uuid);
            }
            else {
                return $uuid;
            }

        } catch (UnsatisfiedDependencyException $e) {
            die('newUuid: caught exception: ' . $e->getMessage() . '\n');
        }
    }
}