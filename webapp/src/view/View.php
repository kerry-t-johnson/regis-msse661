<?php


namespace msse661\view;


interface View {

    function render(...$args) : string;
}