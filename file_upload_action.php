<?php
require_once 'bootstrap.php';

// TODO - I don't like the way this HTML is written... figure out a better way
try {
    $contentDao = new \msse661\dao\mysql\ContentMysqlDao();
    $userDao = new \msse661\dao\mysql\UserMysqlDao();

    $user = $userDao->getByUuid($_POST['user']);
    $contentSpec = \msse661\util\FileManager::saveUserFile($user, $_FILES['fileToUpload']);

    # User can 'override' certain settings via the form:
    $contentSpec['title'] = !empty($_POST['title']) ? $_POST['title'] : $contentSpec['title'];
    $contentSpec['description'] = $_POST['description'] ?? '';

    # All content starts in the 'pending' state:
    $contentSpec['state'] = 'pending';

    $content = $contentDao->create($contentSpec);

    print "<h3>{$content->getTitle()}</h3>\n";
    print "<div>\n";
    print "<p>Description: {$content->getDescription()}</p>\n";
    print "<p>User: {$user->getFullName()}</p>\n";
    print "<p>Path: {$content->getPath()}</p>\n";
    print "<p>State: {$content->getState()}</p>\n";
    print "<p>Hash: {$content->getHash()}</p>\n";
    print "</div>\n";
    print "<a href='./index'>Main page</a>\n";

}
catch (Exception $ex) {
    print "<h3>Upload error</h3>\n";
    print "<p>{$ex->getMessage()}</p>\n";
}

?>

