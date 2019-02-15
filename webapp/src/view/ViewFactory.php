<?php


namespace msse661\view;


class ViewFactory {

    public static function render($contentType, $variables = [], $viewType = null): string {
        $viewFile   = $viewType ? "{$contentType}.{$viewType}.tmpl.php" : "{$contentType}.tmpl.php";
        $viewFile   = dirname(__FILE__) . DIRECTORY_SEPARATOR . strtolower($viewFile);

        if(file_exists($viewFile)) {
            // https://stackoverflow.com/questions/11905140/php-pass-variable-to-include

            // Extract the variables to a local namespace
            extract($variables);

            // Start output buffering
            ob_start();

            // Include the template file
            /** @noinspection PhpIncludeInspection */
            include $viewFile;

            // End buffering and return its contents
            return ob_get_clean();
        }
        else {
            throw new \Exception("No such view: {$viewFile}");
        }
    }

}