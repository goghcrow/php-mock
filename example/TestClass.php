<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/7/26
 * Time: 上午12:35
 */
namespace Xiaofeng\Test\Example;

/**
 * Class TestClass
 * @package Xiaofeng\Test\Example
 */
class TestClass {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function say($something) {
        return __METHOD__ . "($something)";
    }

    private static function staticMethod() {
        $args = implode(", ", func_get_args());
        return __METHOD__ . "($args)";
    }

    private function privateMethod() {
        $args = implode(", ", func_get_args());
        return __METHOD__ . "($args)";
    }
}