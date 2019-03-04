<?php


namespace msse661\dao;


class Schema {

    public function getTablesSchema(): array {

        return [
            'variable' => [
                'columns' => [
                    'name' => [
                        'type' => 'VARCHAR(255)',
                    ],

                    'value' => [
                        'type' => 'VARCHAR(2048)',
                        'null-ok' => true,
                    ],
                ],

                'track-updates' => true,

                'primary-key' => 'name',
            ],

            'users' => [
                'columns' => [
                    'id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'email' => [
                        'type' => 'VARCHAR(512)',
                    ],

                    'first_name' => [
                        'type' => 'VARCHAR(255)',
                    ],

                    'last_name' => [
                        'type' => 'VARCHAR(255)',
                    ],

                    'hashed_password'   => [
                        'type'  => 'VARCHAR(255)',
                    ],
                ],

                'track-updates' => true,

                'primary-key' => 'id',

                'unique-keys' => [
                    'email' => [
                        'email',
                    ],
                ],
            ],

            'content_state' => [
                'columns' => [
                    'id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'name' => [
                        'type' => 'VARCHAR(255)',
                    ],

                    'description' => [
                        'type' => 'VARCHAR(1024)',
                    ],
                ],

                'track-updates' => true,

                'primary-key' => 'id',

                'unique-keys' => [
                    'name' => [
                        'name',
                    ],
                ],

                'post-queries' => [
                    'INSERT IGNORE INTO content_state (id, name, description) VALUES(UUID(), "pending", "Content has not been approved by moderator")',
                    'INSERT IGNORE INTO content_state (id, name, description) VALUES(UUID(), "approved", "Content has been approved by moderator")',
                ],
            ],

            'content' => [
                'columns' => [
                    'id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'title' => [
                        'type'  => 'VARCHAR(1024)',
                    ],

                    'description' => [
                        'type'      => 'VARCHAR(2048)',
                        'null-ok'   => true,
                    ],

                    'users' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'state' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'path' => [
                        'type' => 'VARCHAR(1024)',
                    ],

                    'mime_type' => [
                        'type' => 'VARCHAR(256)',
                    ],

                    'hash'  => [
                        'type'  => 'VARCHAR(40)',
                    ],

                    'comments_allowed' => [
                        'type' => 'TINYINT(1)',
                        'default' => 1,
                    ],

                ],

                'track-updates' => true,

                'primary-key' => 'id',

                'foreign-keys' => [
                    'users' => [
                        'reference-table' => 'users',
                        'reference-column' => 'id',
                        'on-delete-action' => 'RESTRICT',
                    ],

                    'state' => [
                        'reference-table' => 'content_state',
                        'reference-column' => 'id',
                        'on-delete-action' => 'RESTRICT',
                    ],
                ],

                'unique-keys' => [
                    'hash' => [
                        'hash',
                    ],
                ],
            ],

            'tag' => [
                'columns' => [
                    'id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'name' => [
                        'type' => 'VARCHAR(255)',
                    ],

                    'description' => [
                        'type' => 'VARCHAR(2048)',
                        'null-ok' => true,
                    ],

                    'parent' => [
                        'type'      => 'VARCHAR(36)',
                        'null-ok'   => true,
                    ],
                ],

                'track-updates' => true,

                'primary-key' => 'id',

                'foreign-keys' => [
                    'parent' => [
                        'reference-table' => 'tag',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],

                ],

                'unique-keys' => [
                    'name' => [
                        'name',
                    ],
                ],
            ],

            'content_tag' => [
                'columns' => [
                    'content_id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'tag_id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                ],

                'track-updates' => true,

                'foreign-keys' => [
                    'content_id' => [
                        'reference-table' => 'content',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],

                    'tag_id' => [
                        'reference-table' => 'tag',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],
                ],

                'unique-keys' => [
                    'id' => [
                        'content_id',
                        'tag_id',
                    ]
                ]
            ],

            'comments' => [
                'columns' => [
                    'id' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'title' => [
                        'type' => 'VARCHAR(512)',
                        'null-ok' => true,
                    ],

                    'text' => [
                        'type' => 'VARCHAR(2048)',
                    ],

                    'is_question' => [
                        'type' => 'TINYINT(1)',
                        'default' => 0,
                    ],

                    'parent' => [
                        'type' => 'VARCHAR(36)',
                        'null-ok' => true,
                    ],

                    'content' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'users' => [
                        'type' => 'VARCHAR(36)',
                    ],
                ],

                'track-updates' => true,

                'primary-key' => 'id',

                'foreign-keys' => [
                    'parent' => [
                        'reference-table' => 'comments',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],

                    'content' => [
                        'reference-table' => 'content',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],

                    'users' => [
                        'reference-table' => 'users',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],
                ],

            ],

            'content_vote' => [
                'columns' => [
                    'content' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'users' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'vote' => [
                        'type' => 'TINYINT(1)',
                    ],
                ],

                'track-updates' => true,

                'foreign-keys' => [
                    'content' => [
                        'reference-table' => 'content',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],

                    'users' => [
                        'reference-table' => 'users',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],
                ],

                'unique-keys' => [
                    'content_vote' => [
                        'content',
                        'users',
                    ]
                ]
            ],

            'comment_vote' => [
                'columns' => [
                    'comments' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'users' => [
                        'type' => 'VARCHAR(36)',
                    ],

                    'vote' => [
                        'type' => 'TINYINT(1)',
                    ],
                ],

                'track-updates' => true,

                'foreign-keys' => [
                    'comments' => [
                        'reference-table' => 'comments',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],

                    'users' => [
                        'reference-table' => 'users',
                        'reference-column' => 'id',
                        'on-delete-action' => 'CASCADE',
                    ],
                ],

                'unique-keys' => [
                    'comment_vote' => [
                        'comments',
                        'users',
                    ]
                ]
            ],
        ];

    }

    public function getViewsSchema(): array {
        return [
            'content_view' => <<<____________CONTENT_VIEW
                SELECT  content.*,
                        content_state.name  AS state_name
                FROM    content,
                        content_state
                WHERE   content.state = content_state.id
____________CONTENT_VIEW
            ,
            'comments_view' => <<<____________COMMENTS_VIEW
                SELECT    comments.*,
                          content_view.users      AS content_users,
                          content_view.state      AS content_state,
                          content_view.path       AS content_path,
                          content_view.created    AS content_created,
                          content_view.updated    AS content_updated,
                          content_view.state_name AS content_state_name,
                          SUM(comment_vote.vote)  AS comment_vote_total
                FROM      comments,
                          content_view,
                          comment_vote
                WHERE     comments.content = content_view.id
                AND       comment_vote.comments = comments.id
                GROUP BY  comments.id
____________COMMENTS_VIEW
            ,
        ];

    }

}