<?php

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
        'title' => 'Content 1',
        'state' => 'published',
        'path' => '10/short/headers',
        'user_id' => 'user-id-1',
    ],

    'content-id-2' => [
        'title' => 'Content 2',
        'state' => 'published',
        'path' => '5/medium/headers/code',
        'user_id' => 'user-id-1',
    ],

    'content-id-3' => [
        'title' => 'Content 3',
        'state' => 'published',
        'path' => '10/short/headers/ul/ol',
        'user_id' => 'user-id-1',
    ]
];

$test_comments = [
    'comment-id-1' => [
        'text' => 'This is a Question about Content 1',
        'content_id' => 'content-id-1',
        'user_id' => 'user-id-2',
    ],

    'comment-id-20' => [
        'text' => 'This is a Question about Content 2',
        'content_id' => 'content-id-2',
        'user_id' => 'user-id-2',
    ],

    'comment-id-333' => [
        'text' => 'This is a Question about Content 3',
        'content_id' => 'content-id-3',
        'user_id' => 'user-id-2',
    ],
    
    'comment-id-1' => [
        'text' => 'This is an answer to Question 1',
        'question_id' => 'comment-id-1',
        'user_id' => 'user-id-1',
    ],

    'comment-id-11' => [
        'text' => 'This is also an answer to Question 1',
        'question_id' => 'comment-id-1',
        'user_id' => 'user-id-1',
    ],

    'comment-id-2' => [
        'text' => 'This is an answer to Question 20',
        'question_id' => 'comment-id-20',
        'user_id' => 'user-id-1',
    ],

    'comment-id-22' => [
        'text' => 'This is also an answer to Question 20',
        'question_id' => 'comment-id-20',
        'user_id' => 'user-id-1',
    ],

    'comment-id-222' => [
        'text' => 'This is yet another answer to Question 20',
        'question_id' => 'comment-id-20',
        'user_id' => 'user-id-1',
    ],

    'comment-id-3' => [
        'text' => 'This is an answer to Question 333',
        'question_id' => 'comment-id-333',
        'user_id' => 'user-id-1',
    ],
];
