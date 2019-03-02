<?php


namespace msse661\controller;


use msse661\Content;
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

    public function render(array $content, $view = null): string {
        $this->logger->debug('render', ['content' => $content, 'view' => $view]);
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
                    $whereStr   .= "state_name = ':state'";
                    $whereValues['state'] = $value;
                    break;
            }

            $conjunction .= ' AND ';
        }

        return $this->entityDao->fetchWhere(
            $whereStr,
            $whereValues,
            $request['query']['offset'] ?? 0,
            $request['query']['limit'] ?? 0);
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

        return $content;
    }

    public function onGetCreateTestData(array $request) {
        $testDataFactory = new TestDataFactory();
        $testDataFactory->createTestData(dirname(__FILE__) . '/../../../test_data.json');

        $this->redirect("/");
    }
}