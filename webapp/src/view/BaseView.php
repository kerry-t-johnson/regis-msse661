<?php


namespace msse661\view;


class BaseView implements View {

    private $viewFile;

    public function __construct($contentType, $viewType = null) {
        $this->viewFile = $viewType ? "{$contentType}.{$viewType}.tmpl.php" : "{$contentType}.tmpl.php";
        $this->viewFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . strtolower($this->viewFile);

        if(!file_exists($this->viewFile)) {
            throw new \Exception("No such view: {$this->viewFile}");
        }
    }

    public function render(array $variables): string {
        // https://stackoverflow.com/questions/11905140/php-pass-variable-to-include

        // Extract the variables to a local namespace
        extract($variables);

        // Start output buffering
        ob_start();

        // Include the template file
        /** @noinspection PhpIncludeInspection */
        include $this->viewFile;

        // End buffering and return its contents
        return ob_get_clean();
    }

    public function __invoke($variables) {
        return $this->render($variables);
    }

}