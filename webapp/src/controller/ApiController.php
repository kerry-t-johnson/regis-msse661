<?php


namespace msse661\controller;


use msse661\Entity;

class ApiController extends BaseController implements Controller {

    public function __construct() {
        parent::__construct(null);
    }

    public function route(array $request, callable $dataTransform = null) {
        try {
            $result = $this->invokeOtherController($request, $this);

            header('Content-type: application/json');
            print $result;
            http_response_code(200);

            die;
        }
        catch(\Exception $ex) {
            http_response_code($ex->getCode());
            print json_encode(['message' => $ex->getMessage()]);
            die;
        }
    }

    public function __invoke($data) {
        return json_encode($data);
    }

    public function getResource(array $request) {
        throw new \Exception('Unsupported operation');
    }

}