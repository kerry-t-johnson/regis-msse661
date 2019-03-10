<?php


namespace  msse661\controller;


use Monolog\Logger;
use msse661\util\logger\LoggerManager;
use msse661\view\View;
use msse661\view\ViewFactory;

class SiteController {

    /** @var \Monolog\Logger */
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
        $request_query_tmp  = !empty($current_uri_parts['query']) ? explode('&', $current_uri_parts['query']) : [];

        foreach($request_query_tmp as $query) {
            $query_parts    = explode('=', $query);
            $query_key      = $query_parts[0];
            $query_value    = count($query_parts) > 1 ? $query_parts[1] : null;

            self::$logger->debug('currentQueryArgs', ['query_key' => $query_key, 'query_value' => $query_value, 'request_query' => $request_query]);
            if(isset($request_query[$query_key])) {
                if(is_array($request_query[$query_key])) {
                    $request_query[$query_key][] = $query_value;
                }
                else {
                    $original = $request_query[$query_key];
                    $request_query[$query_key] = [$original, $query_value];
                }
            }
            else {
                $request_query[$query_key] = $query_value;
            }
        }

        return $request_query;
    }

    private static function extractRequest(string $path = null) {
        $current_uri_parts  = self::currentUriParts($path);
        $current_path       = self::currentPath($current_uri_parts);
        $current_query      = self::currentQueryArgs($current_uri_parts);

        return ['type' => $_SERVER['REQUEST_METHOD'], 'path'  => $current_path, 'query' => $current_query, 'uri' => $_SERVER['REQUEST_URI']];
    }

    public static function route(string $path = null) {
        if(self::$logger == null) {
            self::$logger = LoggerManager::getLogger('SiteController');
        }

        // Don't attempt to load an entity controller for known file extensions:
        if(preg_match('@\.(ico|png|css)@', $_SERVER['REQUEST_URI'])) {
            return false;
        }

        $request    = SiteController::extractRequest($path);
        if(isset($request['query']['route'])) {
            $request['path'] = explode('/', trim($request['query']['route'], '/'));
            unset($request['query']['route']);
        }
        $router     = new BaseController(null);

        return $router->route($request, [SiteController::class, 'identity']);
    }

    public static function routeApi(string $path = null) {
        if(self::$logger == null) {
            self::$logger = LoggerManager::getLogger('SiteController');
        }

        $request    = SiteController::extractRequest($path);
        $request['path'] = explode('/', trim($request['query']['route'], '/'));
        unset($request['query']['route']);

        self::$logger->debug('routeApi', ['request' => $request]);

        $router     = new BaseController(null);

        return $router->route($request, [SiteController::class, 'identity']);
    }

    public static function identity($data) {
        return $data;
    }

}
