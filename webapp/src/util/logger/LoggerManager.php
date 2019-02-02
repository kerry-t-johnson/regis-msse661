<?php


namespace msse661\util\logger;


use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

class LoggerManager {

    public static function getLogger(string $loggerName) {
        $logger = new Logger($loggerName);
        $logger->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::DEBUG));

        return $logger;
    }

}