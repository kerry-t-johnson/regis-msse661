<?php


namespace msse661\view;


interface View {

    function render(array $args) : string;
}