<?php


namespace msse661\controller;


use msse661\dao\mysql\UserMysqlDao;
use msse661\PianoException;
use msse661\User;
use msse661\view\ViewFactory;

class TagController extends BaseController implements Controller {


    public function __construct() {
        parent::__construct('tag');
    }

    public function render($data, $view = null) : string {
        $this->logger->debug('render', ['data' => $data]);
        return ViewFactory::render(
            'tag',
            ['tag' => $data],
            $view ?? (is_array($data) ? 'list' : null));
    }

}
