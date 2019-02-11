<?php


namespace Msse661\util;


use msse661\User;
use msse661\util\logger\LoggerManager;

class FileManager {

    public static function saveUserFile(User $user, array $sourceSpec) : array {
        $logger = LoggerManager::getLogger(basename(__FILE__, '.php'));

        $file_loc_temp      = $sourceSpec['tmp_name'];
        $file_name          = $sourceSpec['name'];
        $file_loc_permanent = "content/{$user->getUuid()}/{$file_name}";

        $contentDao     = new \msse661\dao\mysql\ContentMysqlDao();
        $file_hash      = sha1_file($file_loc_temp);

        if($contentDao->hashExists($file_hash)) {
            throw new \RuntimeException('Identical file contents already exist');
        }

        if(file_exists($file_loc_permanent)) {
            throw new \Exception("File already exists: {$file_name}");
        }

        $dest_directory     = dirname($file_loc_permanent);
        $dest_directory_rdy = file_exists($dest_directory) || mkdir($dest_directory, 0770, true);

        if(!$dest_directory_rdy) {
            throw new \RuntimeException("Server error: Unable to create upload directory for {$user->getFullName()}.");
        }

        if(!move_uploaded_file($sourceSpec['tmp_name'], $file_loc_permanent)) {
            throw new \RuntimeException("Unable to move uploaded file: {$file_name}");
        }

        $result =   [
            'title' => $file_name,
            'users' => $user->getUuid(),
            'path'  => $file_loc_permanent,
            'hash'  => $file_hash,
        ];

        $logger->info("Created file for user {$user->getFullName()}", $result);

        return $result;
    }

}