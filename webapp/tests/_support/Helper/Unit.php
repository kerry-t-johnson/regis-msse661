<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

use Monolog\Logger;
use msse661\util\logger\LoggerManager;
use msse661\util\TestDataFactory;

class Unit extends \Codeception\Module
{
    /** @var Logger */
    private $logger;

    public function _initialize() {
        parent::_initialize();

        $this->logger = LoggerManager::getLogger('Unit');

        $host   = $_ENV['MYSQL_HOST'] ?? 'localhost';
        $user   = $_ENV['MYSQL_USER'] ?? 'regis';
        $pass   = $_ENV['MYSQL_PASSWORD'] ?? 'regis123';
        $db     = $_ENV['MYSQL_DATABASE'] ?? 'regis';

        $mysql = new \mysqli($host, $_ENV['MYSQL_ROOT_USER'] ?? 'msse661_admin', $_ENV['MYSQL_ROOT_PASSWORD'] ?? 'password');

        $mysql->query("CREATE USER IF NOT EXISTS '{$user}'@'{$host}' IDENTIFIED BY '{$pass}';");
        $mysql->query("GRANT USAGE ON *.* TO '{$user}'@'{$host}'");
        $mysql->query("DROP DATABASE IF EXISTS {$db}");
        $mysql->query("CREATE DATABASE {$db}");
        $mysql->query("GRANT ALL PRIVILEGES ON `${db}`.* TO '{$db}'@'{$host}'");

        $testDataFactory = new TestDataFactory();
        $testDataFactory->createTestData(dirname(__FILE__) . '/../../../../test_data.json');
    }

}