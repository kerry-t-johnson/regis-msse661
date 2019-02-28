<?php


namespace msse661\controller;


interface Controller {

    function route(array $request, callable $dataTransform = null);

    function getResource(array $request);
}