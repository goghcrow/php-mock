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

// 2. example: 类替换

class TestClassMock {
    // mock 返回结果
    public function say($something) {
        return "Mock\\" . __METHOD__ . "($something)";
    }

    // 将 private static 修改成 public 并mock
    public function staticMethod() {
        $args = implode(", ", func_get_args());
        return "Mock\\" . __METHOD__ . "($args)";
    }

    // 将 private 修改成public 并mock返回结果
    public function privateMethod() {
        $args = implode(", ", func_get_args());
        return "Mock\\" . __METHOD__ . "($args)";
    }
}


// 用TestClassMock的同名方法mockTestClass
$mock = new MockClass(TestClass::class);
$mock->mockByObject(new TestClassMock);



// Magic!!!
// 对被mock的类透明
$testClass = new TestClass("xiaofeng");
assert($testClass instanceof TestClass);
// 调用mock后的方法
echo $testClass->say("~"), PHP_EOL;
echo $testClass->staticMethod(1,2,3), PHP_EOL;
echo $testClass->privateMethod(1,2,3), PHP_EOL;


// output:
/*
Mock\Xiaofeng\Test\Example\TestClassMock::say(~)
Mock\Xiaofeng\Test\Example\TestClassMock::staticMethod(1, 2, 3)
Mock\Xiaofeng\Test\Example\TestClassMock::privateMethod(1, 2, 3)
*/