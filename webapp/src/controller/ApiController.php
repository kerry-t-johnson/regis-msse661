<?php


namespace msse661\controller;


use msse661\Entity;

class ApiController extends BaseController implements Controller {

    public function __construct() {
        parent::__construct(null);
    }

    protected function routeImpl(array $request, callable $dataTransform, $resource = null) {
        try {
            $this->invokeOtherController($request, $this);
        }
        catch(\Exception $ex) {
            http_response_code($ex->getCode());
            print json_encode(['message' => $ex->getMessage()]);
            die;
        }
    }

    public function __invoke($data) {
        header('Content-type: application/json');
        print json_encode($data);
        http_response_code(200);

        die;
    }

    public function getResource(array $request) {
        throw new \Exception('Unsupported operation');
    }

}