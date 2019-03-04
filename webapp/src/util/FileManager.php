<?php


namespace Msse661\util;


use msse661\PianoException;
use msse661\User;
use msse661\util\logger\LoggerManager;

class FileManager {

    const ACCEPTABLE_MIME_TYPES = [ 'application/pdf', 'text/html', 'text/plain' ];

    public static function saveUserFile(User $user, array $sourceSpec, bool $isTestData = false) : array {
        $logger = LoggerManager::getLogger(basename(__FILE__, '.php'));

        $file_loc_temp      = $sourceSpec['tmp_name'];
        $file_name          = $sourceSpec['name'];
        $file_loc_permanent = "content/{$user->getUuid()}/{$file_name}";

        $file_info          = finfo_open(FILEINFO_MIME_TYPE);
        $file_mime_type     = finfo_file($file_info, $file_loc_temp);

        if(! in_array($file_mime_type, FileManager::ACCEPTABLE_MIME_TYPES)) {
            throw new PianoException('Invalid file type', 403);
        }

        $contentDao     = new \msse661\dao\mysql\ContentMysqlDao();
        $file_hash      = sha1_file($file_loc_temp);

        if($contentDao->hashExists($file_hash)) {
            throw new PianoException('Identical file contents already exist', 409);
        }

        if(file_exists($file_loc_permanent)) {
            throw new PianoException("File already exists: {$file_name}", 409);
        }

        $dest_directory     = dirname($file_loc_permanent);
        $dest_directory_rdy = file_exists($dest_directory) || mkdir($dest_directory, 0770, true);

        if(!$dest_directory_rdy) {
            throw new PianoException("Server error: Unable to create upload directory for {$user->getFullName()}.", 500);
        }

        if($isTestData) {
            if(!rename($sourceSpec['tmp_name'], $file_loc_permanent)) {
                throw new PianoException("Unable to move uploaded file: {$file_name}", 500);
            }
        }
        else if(!move_uploaded_file($sourceSpec['tmp_name'], $file_loc_permanent)) {
            throw new PianoException("Unable to move uploaded file: {$file_name}", 500);
        }

        $result =   [
            'title'     => $file_name,
            'users'     => $user->getUuid(),
            'path'      => $file_loc_permanent,
            'mime_type' => $file_mime_type,
            'hash'      => $file_hash,
        ];

        $logger->info("Created file for user {$user->getFullName()}", $result);

        return $result;
    }

}