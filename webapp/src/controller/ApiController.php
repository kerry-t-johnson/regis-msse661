<?php


namespace msse661\controller;


use msse661\Entity;

class ApiController extends BaseController implements Controller {

    public function __construct() {
        parent::__construct(null);
    }

    public function route(array $request): string {
        $content_type       = array_shift($request['path']);
        $entity_controller  = $this->createEntityController($content_type);

        $entity = $entity_controller->getResource($request, true);

        $result = null;
        if(is_array($entity)) {
            /** @var Entity $e */
            foreach($entity as $e) {
                $result[] = $e->toJson();
            }
        }
        else {
            $result = $entity->toJson();
        }

        header('Content-type: application/json');
        echo json_encode($result);
        http_response_code(200);

        die;
    }

    public function getResource(array $request, bool $allowSubResources = false) {
        // TODO: Implement getResource() method.
    }

}