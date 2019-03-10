<?php


namespace msse661\controller;


use msse661\CommentImpl;
use msse661\dao\CommentDao;
use msse661\dao\mysql\CommentMysqlDao;
use msse661\dao\mysql\UserMysqlDao;
use msse661\PianoException;
use msse661\User;
use msse661\view\ViewFactory;

class CommentController extends BaseController implements Controller {

    /** @var CommentDao */
    private $commentDao;

    public function __construct() {
        parent::__construct('comment');
        $this->commentDao = new CommentMysqlDao();
    }

    public function render($data, $view = null) : string {
        throw new PianoException('Not implemented', 500);
    }

    public function onPostReply($resource, array $request) {
        $this->logger->info('onPostReply', ['resource' => $resource]);
        $commentSpec = json_decode(file_get_contents('php://input'), true);

        $this->logger->debug('onPostReply', ['commentSpec' => $commentSpec]);

        $reply = $this->commentDao->create($commentSpec);

        /** @var CommentImpl $comment */
        $comment = $this->commentDao->getByUuid($resource);
        $comment->setRecentReply($reply);
        return $comment;
    }

}
