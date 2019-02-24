<?php


namespace msse661\controller;


interface Controller {

    function route(array $request) : string;

    function getResource(array $request);
}