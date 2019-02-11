<?php


namespace msse661\controller;


interface Controller {

    function route(array $path, array $query = []);
}