<?php


namespace msse661\controller;


use msse661\Content;
use msse661\dao\mysql\CommentMysqlDao;
use msse661\dao\mysql\ContentMysqlDao;
use msse661\dao\mysql\UserMysqlDao;
use msse661\view\ViewFactory;

class ContentController implements Controller {

    /** @var Content */
    private $contentDoa;

    public function __construct() {
        $this->contentDoa = new ContentMysqlDao();
    }

    public function route(array $path, array $query = []): string {
        $contentUuid    = $path[0] ?? null;
        $userDao        = new UserMysqlDao();
        $commentsDao    = new CommentMysqlDao();

        if($contentUuid) {
            $content    = $this->contentDoa->getByUuid($contentUuid);
            return ViewFactory::render('content', ['content' => $content], $query['view'] ?? null);
        }
        else {
            // TODO Implement pager

            $content    = $this->contentDoa->getAll();
            $users      = [];
            $comments   = [];

            /** @var Content $c */
            foreach($content as $c) {
                $users[$c->getUuid()]       = $userDao->getByUuid($c->getUserUuid());
                $comments[$c->getUuid()]    = $commentsDao->getByContent($c->getUuid());
            }

            return ViewFactory::render(
                'content',
                ['content' => $content, 'users' => $users, 'comments' => $comments],
                'list');
        }
    }
}