<?php


namespace msse661\controller;


use msse661\Content;
use msse661\dao\mysql\CommentMysqlDao;
use msse661\dao\mysql\ContentMysqlDao;
use msse661\dao\mysql\UserMysqlDao;
use msse661\util\FileManager;
use msse661\view\ViewFactory;

class ContentController implements Controller {

    /** @var Content */
    private $contentDao;

    public function __construct() {
        $this->contentDao = new ContentMysqlDao();
    }

    public function route(array $path, array $query = []): string {
        $contentUuid    = $path[0] ?? null;
        $userDao        = new UserMysqlDao();
        $commentsDao    = new CommentMysqlDao();

        if($contentUuid) {
            $content    = $this->contentDao->getByUuid($contentUuid);
            $user       = $userDao->getByUuid($content->getUserUuid());
            $comments   = $commentsDao->getByContent($contentUuid);

            return ViewFactory::render('content', ['content' => $content, 'user' => $user, 'comments' => $comments], $query['view'] ?? null);
        }
        else {
            // TODO Implement pager

            $content    = $this->contentDao->getAll();
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

    public function uploadForm(array $path, array $query = []): string {
        $user = UserController::getCurrentUser();
        return ViewFactory::render('content', ['user' => $user], 'upload');
    }

    public function upload(): string {
        try {
            $user = UserController::getCurrentUser();
            if ($user->getUuid() != $_POST['userUuid']) {
                throw new \Exception("User mismatch.  Expected: {$_POST['userUuid']}, Actual: {$user->getUuid()}");
            }

            $contentSpec = FileManager::saveUserFile($user, $_FILES['fileToUpload']);

            # User can 'override' certain settings via the form:
            $contentSpec['title'] = !empty($_POST['title']) ? $_POST['title'] : $contentSpec['title'];
            $contentSpec['description'] = $_POST['description'] ?? '';

            # All content starts in the 'pending' state:
            $contentSpec['state'] = 'pending';

            $content = $this->contentDao->create($contentSpec);

            return ViewFactory::render('content', ['content' => $content, 'user' => $user]);
        }
        catch(\Exception $ex) {

        }
    }
}