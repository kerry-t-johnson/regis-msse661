<?php
define('APP_TEST_ENV', 'APP_TEST_ENV');

require_once 'bootstrap.php';

$test_users = [
    'user-id-1' => [
        'email'         => 'fred.flintstone@gmail.com',
        'first_name'    => 'Fred',
        'last_name'     => 'Flintstone',
    ],

    'user-id-2' => [
        'email'         => 'barney.rubble@gmail.com',
        'first_name'    => 'Barney',
        'last_name'     => 'Rubble',
    ]
];

$test_content = [
    'content-id-1' => [
        'title'         => 'Test Content 1',
        'state'         => 'approved',
        'path'          => '10/short/headers',
        'hash'          => sha1('Test Content 1'),
        'test_user_id'  => 'user-id-1',
    ],

    'content-id-2' => [
        'title'         => 'Test Content 2',
        'state'         => 'approved',
        'path'          => '5/medium/headers/code',
        'hash'          => sha1('Test Content 2'),
        'test_user_id'  => 'user-id-1',
    ],

    'content-id-3' => [
        'title'         => 'Test Content 3',
        'state'         => 'approved',
        'path'          => '10/short/headers/ul/ol',
        'hash'          => sha1('Test Content 3'),
        'test_user_id'  => 'user-id-1',
    ]
];

$test_comments = [
    'comment-id-1' => [
        'title'             => 'Question 1',
        'text'              => 'This is a Question about Content 1',
        'test_content_id'   => 'content-id-1',
        'test_user_id'      => 'user-id-2',
    ],

    'comment-id-20' => [
        'title'             => 'Question 2',
        'text'              => 'This is a Question about Content 2',
        'test_content_id'   => 'content-id-2',
        'test_user_id'      => 'user-id-2',
    ],

    'comment-id-333' => [
        'title'             => 'Question 3',
        'text'              => 'This is a Question about Content 3',
        'test_content_id'   => 'content-id-3',
        'test_user_id'      => 'user-id-2',
    ],

];

function createTestData() {
    global $test_users;
    global $test_content;
    global $test_comments;

    $actual_users = [];
    foreach($test_users as $testId => &$userSpec) {
        $actual_users[$testId] = createOrRetrieveTestUser($userSpec);
    }

    $actual_content = [];
    foreach($test_content as $testId => &$contentSpec) {
        /** @var $user \msse661\User*/
        $user   = $actual_users[$contentSpec['test_user_id']];

        $contentSpec['users'] = $user->getUuid();

        $actual_content[$testId] = createOrRetrieveTestContent($contentSpec);
    }

    $actual_comments = [];
    foreach($test_comments as $testId => &$commentSpec) {
        /** @var $content \msse661\Content*/
        $content    = $actual_content[$commentSpec['test_content_id']];
        /** @var $user \msse661\User*/
        $user       = $actual_users[$commentSpec['test_user_id']];

        $commentSpec['content'] = $content->getUuid();
        $commentSpec['users']   = $user->getUuid();

        $actual_comments[$testId] = createOrRetrieveTestComments($commentSpec);
    }
}


function createOrRetrieveTestUser($userSpec): \msse661\User {
    $userDao = new \msse661\dao\mysql\UserMysqlDao();

    try {
        return $userDao->getByEmail($userSpec['email']);
    }
    catch (Exception $e) {
        return $userDao->create($userSpec);
    }
}

function createOrRetrieveTestContent($contentSpec): \msse661\Content {
    $contentDao = new \msse661\dao\mysql\ContentMysqlDao();

    $content = $contentDao->getByTitleLike($contentSpec['title']);

    if(count($content) > 0) {
        return $content[0];
    }
    else {
        return $contentDao->create($contentSpec);
    }
}

function createOrRetrieveTestComments($commentSpec): \msse661\Comment {
    $commentDao = new \msse661\dao\mysql\CommentMysqlDao();

    $comment = $commentDao->getByTitleLike($commentSpec['title']);

    if(count($comment)) {
        return $comment[0];
    }
    else {
        return $commentDao->create($commentSpec);
    }
}

$db = new \msse661\dao\mysql\MysqlDatabase(new \msse661\dao\Schema());
createTestData();

ob_start();
header('Location: /');
ob_end_flush();
die();