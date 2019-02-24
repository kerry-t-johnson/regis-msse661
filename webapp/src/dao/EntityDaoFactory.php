<?php


namespace msse661\dao;


class EntityDaoFactory {

    public static function createEntityDao($entity_type) {
        $entity_type    = ucwords($entity_type);
        $entity_dao     = "\\msse661\\dao\\mysql\\{$entity_type}MysqlDao";

        if(class_exists($entity_dao)) {
            return new $entity_dao();
        }
        else {
            throw new \Exception('Unable to locate Entity DAO: ' . $entity_dao);
        }
    }

}