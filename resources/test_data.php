<?php
/**
 * Although we are not required to use a database this week, these data structures somewhat resemble a DB
 * relationship which might be used for the nano-site project.
 *
 * That is, the top-level keys in each array emulate a unique DB identifier for an item.
 *
 * The values are associative arrays where the keys emulate column names and values emulate DB content.
 */

$users = [
    'user-id-1' => [
        'first_name' => 'Fred',
        'last_name' => 'Flintstone',
    ],

    'user-id-2' => [
        'first_name' => 'Barney',
        'last_name' => 'Rubble',
    ]
];

$content = [
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

$questions = [
    'question-id-1' => [
        'text' => 'This is Question about Content 1',
        'content_id' => 'content-id-1',
        'user_id' => 'user-id-2',
    ],

    'question-id-20' => [
        'text' => 'This is a Question about Content 2',
        'content_id' => 'content-id-2',
        'user_id' => 'user-id-2',
    ],

    'question-id-333' => [
        'text' => 'This is a Question about Content 3',
        'content_id' => 'content-id-3',
        'user_id' => 'user-id-2',
    ]
];

$answers = [
    'answer-id-1' => [
        'text' => 'This is an answer to Question 1',
        'question_id' => 'question-id-1',
        'user_id' => 'user-id-1',
    ],

    'answer-id-11' => [
        'text' => 'This is also an answer to Question 1',
        'question_id' => 'question-id-1',
        'user_id' => 'user-id-1',
    ],

    'answer-id-2' => [
        'text' => 'This is an answer to Question 20',
        'question_id' => 'question-id-20',
        'user_id' => 'user-id-1',
    ],

    'answer-id-22' => [
        'text' => 'This is also an answer to Question 20',
        'question_id' => 'question-id-20',
        'user_id' => 'user-id-1',
    ],

    'answer-id-222' => [
        'text' => 'This is yet another answer to Question 20',
        'question_id' => 'question-id-20',
        'user_id' => 'user-id-1',
    ],

    'answer-id-3' => [
        'text' => 'This is an answer to Question 333',
        'question_id' => 'question-id-333',
        'user_id' => 'user-id-1',
    ],
];
