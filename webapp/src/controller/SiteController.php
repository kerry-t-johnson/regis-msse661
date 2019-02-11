<?php


namespace  msse661\controller;


class SiteController implements Controller {

    public static function extractRequest(array $request) {
        $doc_root       = dirname($request['SCRIPT_NAME']);
        $request_uri    = $request['REQUEST_URI'];
        $request_uri    = str_replace($doc_root, '', $request_uri);
        $request_uri    = strtolower($request_uri);
        $request_parts  = parse_url($request_uri);

        $request_path = explode('/', trim($request_parts['path'], '/'));

        $request_query      = [];
        $request_query_tmp  = !empty($request_parts['query']) ? explode('?', $request_parts['query']) : [];
        foreach($request_query_tmp as $query) {
            $query_parts    = explode('=', $query);
            $request_query[$query_parts[0]] = count($query_parts) > 1 ? $query_parts[1] : null;
        }

        return ['path'  => $request_path, 'query' => $request_query];
    }

    public function route(array $path, array $query = []) {
        $entity_type        = ucwords($path[0]);
        $entity_controller  = "\msse661\controller\{$entity_type}Controller";

        if(class_exists($entity_controller)) {
            /** @var Controller $entity_controller */
            $entity_controller = new $entity_controller();
            $entity_controller->route(array_shift($path), $query);
        }
    }

}