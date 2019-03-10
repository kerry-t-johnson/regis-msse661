<?php


namespace msse661\controller;


use msse661\Content;
use msse661\dao\CommentDao;
use msse661\dao\EntityDaoFactory;
use msse661\dao\mysql\CommentMysqlDao;
use msse661\dao\mysql\ContentMysqlDao;
use msse661\dao\mysql\TagMysqlDao;
use msse661\PianoException;
use msse661\util\FileManager;
use msse661\util\TestDataFactory;
use msse661\view\ViewFactory;

class ContentController extends BaseController implements Controller {

    /** @var Content */
    private $contentDao;

    public function __construct() {
        parent::__construct('content');
        $this->contentDao = new ContentMysqlDao();
    }

    public function render($content, $view = null): string {
        // $this->logger->debug('render', ['content' => $content, 'view' => $view]);
        if(is_array($content)) {
            return ViewFactory::render(
                'content',
                ['content' => $content],
                $view ?? 'list');
        }
        else {
            $contentText    = filter_var($content->getPath(), FILTER_VALIDATE_URL) && strpos($content->getPath(), 'api')
                ? file_get_contents($content->getPath())
                : false;
            $contentLink    = filter_var($content->getPath(), FILTER_VALIDATE_URL) && $contentText === false0
                ? $content->getPath()
                : false;

            return ViewFactory::render(
                'content',
                ['content' => $content,
                 'contentText' => $contentText,
                 'contentLink' => $contentLink],
                $request['query']['view'] ?? null);
        }
    }

    public function onDeleteDelete(string $resource) {
        $this->logger->info('onDeleteDelete', ['resource' => $resource]);
        $this->contentDao->delete($resource);

        return ['result' => 'success'];
    }

    public function onGetUploadForm(array $request): string {
        $user = UserController::getCurrentUser();
        return ViewFactory::render('content', ['user' => $user], 'upload');
    }

    public function onGetUser(string $resource, array $request) {
        return $this->entityDao->fetchExactlyOne('id', $resource)->getUser();
    }

    public function onGetTag(string $resource, array $request) {
        $this->logger->debug('onGetTag', ['resource' => $resource, 'request' => $request]);
        $tagDao = new TagMysqlDao();
        return $tagDao->getTagsByContent($resource);
    }

    public function onGetComments(string $resource, array $request) {
        $this->logger->debug('onGetComments', ['resource' => $resource, 'request' => $request]);
        $commentDao = new CommentMysqlDao();
        return $commentDao->getByContent($resource);
    }

    public function onPostComment(string $resource, array $request) {
        $this->logger->info('onPostComment', ['resource' => $resource]);
        $commentSpec = json_decode(file_get_contents('php://input'), true);

        $this->logger->debug('onPostComment', ['commentSpec' => $commentSpec]);

        /** @var CommentDao $commentDao */
        $commentDao = EntityDaoFactory::createEntityDao('comment');
        return $commentDao->create($commentSpec);
    }

    protected function onSpecializedQuery($request) {
        $whereStr       = '';
        $whereValues    = [];

        $conjunction = '';
        foreach($request['query'] as $key => $value) {
            $whereStr   .= $conjunction;

            switch ($key) {
                case 'title':
                    $whereStr .= "title LIKE '%:title%'";
                    $whereValues['title'] = $value;
                    break;
                case 'description':
                    $whereStr   .= "description LIKE '%:description%";
                    $whereValues['description'] = $value;
                    break;
                case 'state':
                    $whereStr   .= "state_name = :state";
                    $whereValues['state'] = $value;
                    break;
                case 'tag':
                    // Only add once (the first time):
                    if(!isset($whereValues['tags'])) {
                        $whereStr .= 'content_tag.tag_id IN (:tags) AND content_view.id = content_tag.content_id';
                    }
                    $whereValues['tags'] = is_array($value) ? $value : [ $value ];
                    break;
                case 'exclude-user':
                    $whereStr .= 'users != :user';
                    $whereValues['user'] = $value;
                    break;
                default:
                    continue;
            }

            $conjunction .= ' AND ';
        }

        return $this->entityDao->fetchWhere(
            $whereStr,
            $whereValues,
            $request['query']['offset'] ?? 0,
            $request['query']['limit'] ?? 0,
            '',
            isset($whereValues['tags']) ? ['content_tag'] : []);
    }

    public function onPostUpload(array $request) {
        $user = UserController::getCurrentUser();
        $this->logger->debug('onPostUpload', ['_POST' => $_POST, '_FILES' => $_FILES]);
        if ($user->getUuid() != $_POST['content-upload-user-uuid']) {
            throw new PianoException('Not authorized', 401);
        }

        $contentSpec = FileManager::saveUserFile($user, $_FILES['file-to-upload']);

        # User can 'override' certain settings via the form:
        $contentSpec['title'] = !empty($_POST['title']) ? $_POST['title'] : $contentSpec['title'];
        $contentSpec['description'] = $_POST['description'] ?? '';

        # All content starts in the 'pending' state:
        $contentSpec['state'] = 'pending';

        $content = $this->contentDao->create($contentSpec);

        $tagDao = new TagMysqlDao();
        $tagDao->applyTagsToContent($content->getUuid(), $_POST['content-tags']);

        $this->redirect('user/' . $user->getUuid());

        return $content;
    }

    public function onPutEdit(string $resource, array $request) {
        $this->logger->info('onPutEdit', ['resource' => $resource]);

        $contentSpec = json_decode(file_get_contents('php://input'), true);
        $this->logger->debug('onPutEdit', ['input' => $contentSpec]);

        $user = UserController::getCurrentUser();
        if ($user->getUuid() != $contentSpec['users']) {
            throw new PianoException('Not authorized', 401);
        }

        $content = $this->contentDao->update($contentSpec);

        $tagDao = new TagMysqlDao();
        $tagDao->applyTagsToContent($content->getUuid(), $contentSpec['tags'], true);

        $this->redirect('user/' . $user->getUuid());

        return $this->contentDao->getByUuid($contentSpec['id']);
    }

    public function onGetCreateTestData(array $request) {
        $testDataFactory = new TestDataFactory();
        $testDataFactory->createTestData(dirname(__FILE__) . '/../../../test_data.json');

        $this->redirect("/");
    }
}