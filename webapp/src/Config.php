<?php

namespace {
    require_once 'config.php';
}

namespace msse661 {


    class Config {

        public static function getDatabaseConfig() {
            global $database;
            return $database;
        }

    }

}