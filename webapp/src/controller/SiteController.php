<?php


namespace  msse661\controller;


use Monolog\Logger;
use msse661\util\logger\LoggerManager;

class SiteController {

    /** @var Monolog\Logger */
    private static $logger = null;

    public static function currentUri($uri = null) : string {
        $doc_root       = dirname($_SERVER['SCRIPT_NAME']);
        $request_uri    = $uri ?? $_SERVER['REQUEST_URI'];
        $request_uri    = preg_replace("@^{$doc_root}@", '', $request_uri);

        return $request_uri;
    }

    public static function currentUriParts($uri = null) : array {
        return parse_url(self::currentUri($uri));
    }


    public static function currentPath(array $current_uri_parts = []): array {
        if(self::$logger == null) {
            self::$logger = LoggerManager::getLogger('SiteController');
        }

        $current_uri_parts = $current_uri_parts ?? self::currentUriParts();
        $path_string    = $current_uri_parts['path'];

        return explode('/', trim($path_string, '/'));
    }

    public static function currentQueryArgs(array $current_uri_parts = []): array {
        $current_uri_parts = $current_uri_parts ?? self::currentUriParts();

        $request_query      = [];
        $request_query_tmp  = !empty($current_uri_parts['query']) ? explode('?', $current_uri_parts['query']) : [];

        foreach($request_query_tmp as $query) {
            $query_parts    = explode('=', $query);
            $request_query[$query_parts[0]] = count($query_parts) > 1 ? $query_parts[1] : null;
        }

        return $request_query;
    }

    private static function extractRequest() {
        $current_uri_parts  = self::currentUriParts();
        $current_path       = self::currentPath($current_uri_parts);
        $current_query      = self::currentQueryArgs($current_uri_parts);

        return ['path'  => $current_path, 'query' => $current_query, 'uri' => $_SERVER['REQUEST_URI']];
    }

    public static function route($uri = null) : string {
        if(self::$logger == null) {
            self::$logger = LoggerManager::getLogger('SiteController');
        }

        // Don't attempt to load an entity controller for known file extensions:
        if(preg_match('@\.(ico|php|png|css)@', $_SERVER['REQUEST_URI'])) {
            return false;
        }

        $request            = SiteController::extractRequest();

        $entity_type        = ucwords(array_shift($request['path']));
        $entity_type        = !empty($entity_type) ? $entity_type : 'Content';
        $entity_controller  = "\\msse661\\controller\\{$entity_type}Controller";

        self::$logger->debug("route", ['request (extracted)' => $request, 'entity_type' => $entity_type, 'entity_controller' => $entity_controller]);

        if(class_exists($entity_controller)) {
            /** @var Controller $entity_controller */
            $entity_controller  = new $entity_controller();
            $request_path       = $request['path'];

            if(!empty($request_path[0]) && method_exists($entity_controller, $request_path[0])) {
                $function = array_shift($request_path);
                return $entity_controller->{$function}($request_path, $request['query']);
            }
            else {
                return $entity_controller->route($request['path'], $request['query']);
            }
        }
        else {
            return "Unknown route: {$entity_controller}...\n\n" . print_r($request, true);
        }
    }

    public static function redirect($uri) {
        ob_start();
        header("Location: {$uri}");
        ob_end_flush();
        die();
    }

}
