<?php


namespace msse661\view;


use msse661\util\logger\LoggerManager;

class ViewFactory {

    public static function createRenderer($contentType, $viewType = null) {
        return new BaseView($contentType, $viewType);
    }

    public static function render($contentType, $variables = [], $viewType = null): string {
        // $logger = LoggerManager::getLogger('ViewFactory');

        // $logger->debug('render', ['contentType' => $contentType, 'variables' => $variables, 'viewType' => $viewType]);
        $view   = self::createRenderer($contentType, $viewType);

        return $view->render($variables);
    }

}