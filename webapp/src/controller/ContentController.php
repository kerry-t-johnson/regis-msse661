<?php


namespace msse661\controller;


use msse661\Content;
use msse661\dao\mysql\ContentMysqlDao;
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

    public function route(array $request): string {
        $content        = $this->getResource($request);

        if(is_array($content)) {
            return ViewFactory::render(
                'content',
                ['content' => $content],
                $request['query']['view'] ?? 'list');

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

    public function onPostUpload(array $request): string {
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

            $this->redirect("/content/{$content->getUuid()}");
        }
        catch(\Exception $ex) {
            // TODO
        }
    }

    public function onGetCreateTestData(array $request) {
        $testDataFactory = new TestDataFactory();
        $testDataFactory->createTestData(dirname(__FILE__) . '/../../../test_data.json');

        $this->redirect("/");
    }
}