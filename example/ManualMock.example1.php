<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/7/26
 * Time: 上午12:34
 */
namespace Xiaofeng\Test\Example;

use Xiaofeng\Test\MockClass;

require __DIR__ . "/../src/Mock.php";
require __DIR__ . "/TestClass.php";

error_reporting(E_ALL);
ini_set("display_errors", true);


// 1. example: 手动mock

$mock = new MockClass(TestClass::class);

// mock say 方法
$mock->mock("say", function($arg) {
    return "Mock\\say" . "($arg)";
}, \ReflectionMethod::IS_PUBLIC);

// 将private static 方法 修改成public static方法
$mock->mock("staticMethod", function() {
    $args = implode(", ", func_get_args());
    return "Mock\\say" . "($args)";
}, \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC);


$testClass = new TestClass("xiaofeng");
echo "Mock ==> " , $testClass->say("xiaofeng"), PHP_EOL;

echo "UnMock ", TestClass::class . "::say", PHP_EOL;
$mock->unMock("say");

echo $testClass->say("xiaofeng"), PHP_EOL;

echo "Mock ==> " , $testClass->staticMethod(1, 2, 3), PHP_EOL;
$mock->unMock("staticMethod");



$mock->mock("getName", function($addOneArg) {
    return "mock $addOneArg";
});
echo $testClass->getName("arg"), PHP_EOL;

$mock->unMock("getName");
echo $testClass->getName("arg"), PHP_EOL;