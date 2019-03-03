<?php

namespace msse661\dao\mysql;

use msse661\Config;
use msse661\dao\EntityDao;
use msse661\Entity;
use msse661\PianoException;
use msse661\util\logger\LoggerManager;
use mysql_xdevapi\Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;


class BaseMysqlDao extends \mysqli implements EntityDao
{
    protected $table;
    protected $entityClass;
    protected $databaseHost;
    protected $databaseName;
    protected $defaultOrderBy;

    /** @var \Monolog\Logger  */
    protected $logger;

    public function __construct($table, $entity_class, $default_order_by = '') {
        $databaseConfig = Config::getDatabaseConfig();

        $this->table            = $table;
        $this->entityClass      = $entity_class;
        $this->defaultOrderBy   = $default_order_by;
        $this->databaseHost     = $databaseConfig['host'];
        $this->databaseName     = $databaseConfig['database'];
        $this->logger           = LoggerManager::getLogger(get_class($this));

        if(!class_exists($entity_class)) {
            $this->logger->error('__construct: Entity class does not exist', ['entity_class' => $entity_class]);
            throw new \Exception('Entity class does not exist:' . $entity_class);
        }

        parent::__construct(
            $this->databaseHost,
            $databaseConfig['user'],
            $databaseConfig['password'],
            $this->databaseName);
    }

    public function getDatabaseHost(): string {
        return $this->databaseHost;
    }

    public function getDatabaseName(): string {
        return $this->databaseName;
    }

    public function query($query, $resultmode = MYSQLI_STORE_RESULT) {
        $result = null;
        $query  = is_string($query) ? [$query] : $query;

        foreach ($query as $q) {
            $result = parent::query($q, $resultmode);

            if($result === false) {
                throw new \Exception('Error while executing query: ' . $this->error . '(' . $q . ')');
            }
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
        $this->query($query);
         $this->logger->info("Created Entity", $entitySpec);
        return $entitySpec;
    }

    public function fetchExactlyOne(string $key, string $value): ?Entity {
        $query = "SELECT * FROM {$this->table} WHERE {$key} = '{$value}'";

        $result = $this->query($query);

        if($result->num_rows == 0) {
            throw new PianoException("Unable to find object to match {$key} = {$value}.", 404);
        }
        else if($result->num_rows > 1) {
            throw new \Exception("Expected one object to match {$key} = {$value}.  Found {$result->num_rows} objects.");
        }

        $assoc = $result->fetch_assoc();
         $this->logger->debug('fetchExactlyOne', ['assoc' => $assoc, 'entity_class' => $this->entityClass]);

        return new $this->entityClass($assoc);
    }

    protected function countWhere(string $where, array $values): bool {
        $query          = <<<________QUERY
            SELECT  COUNT(*) AS count
            FROM    {$this->table}
            WHERE   {$where}
________QUERY;
        $query  = $this->escapeQuery($query, $values);
        $result = $this->query($query);

        return $result->fetch_assoc()['count'];
    }

    public function fetch(int $offset = 0, int $limit = 0, string $orderBy = ''): array {
        $orderBy        = $orderBy ? $orderBy : $this->defaultOrderBy;
        $limitQuery     = $limit  > 0 ? "LIMIT  {$limit}"  : '';
        $offsetQuery    = $offset > 0 ? "OFFSET {$offset}" : '';
        $orderByQuery   = $orderBy ? "ORDER BY {$orderBy}" : '';
        $query          = <<<________QUERY
            SELECT  *
            FROM    {$this->table}
            {$limitQuery}
            {$offsetQuery}
            {$orderByQuery}
________QUERY;

        $result = $this->query($query);
        $this->logger->debug('fetch', ['query' => $query]);

        $entities = [];
        while ($row = $result->fetch_assoc()) {
            $entities[] = new $this->entityClass($row);
        }
        $this->logger->debug('fetch', ['entities' => $entities]);
        return $entities;
    }

    public function fetchWhere(string $where, array $values, int $offset = 0, int $limit = 0, string $orderBy = ''): array {
        $orderBy        = $orderBy ? $orderBy : $this->defaultOrderBy;
        $limitQuery     = $limit  > 0 ? "LIMIT  {$limit}"  : '';
        $offsetQuery    = $offset > 0 ? "OFFSET {$offset}" : '';
        $orderByQuery   = $orderBy ? "ORDER BY {$orderBy}" : '';
        $query          = <<<________QUERY
            SELECT  *
            FROM    {$this->table}
            WHERE   {$where}
            {$limitQuery}
            {$offsetQuery}
            {$orderByQuery}
________QUERY;
        $query  = $this->escapeQuery($query, $values);

        $result = $this->query($query);

        $this->logger->debug('fetchWhere', ['query' => $query]);

        $entities = [];
        while ($row = $result->fetch_assoc()) {
            $entities[] = new $this->entityClass($row);
        }
        return $entities;
    }

    public function escapeQuery(string $query, array $args): string {
        foreach ($args as $key => $value) {
            // $this->logger->debug('escapeQuery', ['key' => $key, 'value' => $value, 'is_string' => is_string($value)]);
            $query = str_replace(":{$key}", (is_string($value) ? $this->real_escape_string((string)$value) : $value), $query);
        }

        return $query;
    }

    public static function newUuid(): string {
        try {
            $uuid = Uuid::uuid4();

            if(defined('APP_TEST_ENV')) {
                return preg_replace('/(.{8})-(.{4})-/', '\1-test-', $uuid);
            }
            else {
                return $uuid;
            }

        } catch (UnsatisfiedDependencyException $e) {
            // This is not really a runtime error.  This indicates that the site is
            // misconfigured (composer should have downloaded Uuid and its dependencies).
            //
            // If this fails, it's appropriate to 'die'

            die('newUuid: caught exception: ' . $e->getMessage() . '\n');
        }
    }
}