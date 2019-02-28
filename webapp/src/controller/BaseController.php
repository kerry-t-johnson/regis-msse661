<?php


namespace msse661\controller;


use Monolog\Logger;
use msse661\dao\EntityDao;
use msse661\dao\EntityDaoFactory;
use msse661\NoSuchControllerException;
use msse661\NoSuchMethodException;
use msse661\PianoException;
use msse661\util\logger\LoggerManager;

class BaseController implements Controller {

    /** @var Logger */
    protected $logger;

    /** @var string */
    protected $entityType;

    /** @var EntityDao */
    protected $entityDao;

    public function __construct($entity_type) {
        $this->entityType   = $entity_type;
        $this->entityDao    = $entity_type ? EntityDaoFactory::createEntityDao($this->entityType) : null;
        $this->logger       = LoggerManager::getLogger(get_class($this));
    }

    public function route(array $request, callable $dataTransform = null) {
        $this->logger->debug("route", ['request' => $request, 'dataTransform' => $dataTransform]);

        $dataTransform = $dataTransform ? $dataTransform : $this;

        try {
            return call_user_func($dataTransform, $this->invokeEntityMethod($request));
        }
        catch(NoSuchMethodException $ex) {
            try {
                return $this->invokeOtherController($request, $dataTransform);
            }
            catch(NoSuchControllerException $ex) {
                array_shift($request['path']);
                return call_user_func($dataTransform, $this->getResource($request));
            }
        }
    }

    public function render(array $request) {
        throw new \Exception('Operation not implemented');
    }

    public function getResource(array $request) {
        $this->logger->debug('getResource', ['request' => $request]);
        $resource    = !empty($request['path']) ? array_shift($request['path']) : null;

        if($resource) {
            $subAction  = array_shift($request['path']);

            $actionMethod = !empty($subAction) ? ('on' . ucwords(strtolower($request['type'])) . ucwords($subAction)) : false;

            $this->logger->debug('getResource', ['entityUuid' => $resource, 'subAction' => $subAction, 'actionMethod' => $actionMethod]);
            if($actionMethod && method_exists($this, $actionMethod)) {
                return $this->{$actionMethod}($request);
            }
            else if($subAction) {
                /** @var EntityDao $subEntityDao */
                $subEntityDao   = EntityDaoFactory::createEntityDao($subAction);
                return $subEntityDao->fetchWhere("{$this->entityType} = ':entityUuid'", ['entityUuid' => $resource]);
            }
            else {
                return $this->entityDao->fetchExactlyOne('id', $resource);
            }
        }
        else {
            if($this->hasSpecializedQuery($request)) {
                return $this->onSpecializedQuery($request);
            }
            else {
                return $this->onDefaultQuery($request);
            }
        }

    }

    protected function onSpecializedQuery($request) {
        $this->logger->warning('Specialized Query requested, but not implemented');
        return $this->onDefaultQuery($request);
    }

    protected function onDefaultQuery($request) {
        return $this->entityDao->fetch(
            $request['query']['offset'] ?? 0,
            $request['query']['limit'] ?? 0);
    }

    protected function invokeEntityMethod(array $request) {
        $controller_method_name = !empty($request['path'][0]) ? ('on' . ucwords(strtolower($request['type'])) . ucwords($request['path'][0])) : false;

        if ($controller_method_name && method_exists($this, $controller_method_name)) {
            array_shift($request['path']);

            $this->logger->debug("invokeEntityMethod", ['controller_method_name' => $controller_method_name]);

            return $this->{$controller_method_name}($request);
        }
        else {
            throw new NoSuchMethodException($this, $controller_method_name);
        }
    }

    protected function invokeOtherController(array $request, callable $dataTransform) {
        $entity_type        = $request['path'][0] ?? false;
        $entity_type        = $entity_type? ucwords($entity_type) : 'NoSuchController';
        $entity_controller  = "\\msse661\\controller\\{$entity_type}Controller";

        if(class_exists($entity_controller)) {
            array_shift($request['path']);

            $this->logger->debug("invokeOtherController", ['entity_type' => $entity_type, 'entity_controller' => $entity_controller, 'request' => $request]);

            /** @var Controller $other_controller */
            $other_controller   = new $entity_controller();
            return $other_controller->route($request, $dataTransform);
        }
        else {
            throw new NoSuchControllerException(implode('/', $request['path']));
        }
    }

    protected function redirect(string $dest) {
        ob_start();
        header("Location: {$dest}");
        ob_end_flush();
        die();
    }

    public function __invoke($data) {
        return $this->render($data);
    }

    private function hasSpecializedQuery(array $request) {
        if(isset($request['query'])) {
            foreach($request['query'] as $key => $value) {
                if($key != 'offset' && $key != 'limit') {
                    return true;
                }
            }
        }

        return false;
    }
}