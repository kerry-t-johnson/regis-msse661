<?php


namespace msse661\controller;


use Monolog\Logger;
use msse661\dao\EntityDao;
use msse661\dao\EntityDaoFactory;
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

    public function route(array $request): string  {
        $entity_controller  = $this->createEntityController(array_shift($request['path']));
        $entity_controller  = $entity_controller ?? new ContentController();

        $this->logger->debug("route", ['request (extracted)' => $request]);

        $request_path       = $request['path'];

        $entity_method_name = !empty($request_path[0]) ? ('on' . ucwords(strtolower($request['type'])) . ucwords($request_path[0])) : false;

        if($entity_method_name && method_exists($entity_controller, $entity_method_name)) {
            array_shift($request['path']);
            return $entity_controller->{$entity_method_name}($request);
        }
        else {
            return $entity_controller->route($request);
        }
    }


    public function getResource(array $request) {
        $entityUuid    = array_shift($request['path']);

        if($entityUuid) {
            $subEntityType  = array_shift($request['path']);

            $this->logger->debug('getResource', ['entityUuid' => $entityUuid, 'subEntityType' => $subEntityType]);
            if($subEntityType) {
                /** @var EntityDao $subEntityDao */
                $subEntityDao   = EntityDaoFactory::createEntityDao($subEntityType);
                return $subEntityDao->fetchWhere("{$this->entityType} = ':entityUuid'", ['entityUuid' => $entityUuid]);
            }
            else {
                return $this->entityDao->fetchExactlyOne('id', $entityUuid);
            }
        }
        else {
            return $this->entityDao->fetch(
                $request['query']['offset'] ?? 0,
                $request['query']['limit'] ?? 0,
                'created DESC');
        }

    }

    protected function createEntityController($entity_type) : ?Controller {
        $entity_type        = ucwords($entity_type);
        $entity_controller  = "\\msse661\\controller\\{$entity_type}Controller";

        $this->logger->debug("route", ['entity_type' => $entity_type, '(potential) entity_controller' => $entity_controller]);

        if(class_exists($entity_controller)) {
            /** @var Controller $entity_controller */
            return new $entity_controller();
        }
        else {
            throw new \Exception("Unknown route: {$entity_controller}");
        }
    }

    protected function redirect(string $dest) {
        ob_start();
        header("Location: {$dest}");
        ob_end_flush();
        die();
    }

}