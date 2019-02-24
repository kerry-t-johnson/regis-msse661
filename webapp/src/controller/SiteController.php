<?php


namespace  msse661\controller;


use function GuzzleHttp\Psr7\str;
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
        $request_query_tmp  = !empty($current_uri_parts['query']) ? explode('&', $current_uri_parts['query']) : [];

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

        return ['type' => $_SERVER['REQUEST_METHOD'], 'path'  => $current_path, 'query' => $current_query, 'uri' => $_SERVER['REQUEST_URI']];
    }

    public static function route(string $alternatePath = null) : string {
        if(self::$logger == null) {
            self::$logger = LoggerManager::getLogger('SiteController');
        }

        // Don't attempt to load an entity controller for known file extensions:
        if(preg_match('@\.(ico|php|png|css)@', $_SERVER['REQUEST_URI'])) {
            return false;
        }

        if($_SERVER['REQUEST_URI'] == '/' && $alternatePath != null) {
            $_SERVER['REQUEST_URI'] = $alternatePath;
        }

        $request    = SiteController::extractRequest();
        $router     = new BaseController(null);

        return $router->route($request);
    }

}
