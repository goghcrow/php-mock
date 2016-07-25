<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/7/25
 * Time: 下午10:44
 */
namespace Xiaofeng\Test;

use Closure;
use ReflectionClass;

class MockClass {

    protected $className;
    protected $class;
    protected $methods = [];
    protected $backupMethods = [];

    /**
     * MockClass constructor.
     * @param string $className 需要mock的class
     */
    public function __construct($className) {
        $this->className = $className;
        $this->class = new ReflectionClass($className);
        $methods = $this->class->getMethods();
        foreach ($methods as $method) {
            $this->methods[$method->getName()] = $method;
        }
    }

    /**
     * 用mockObj的同名方法替换MockClass的方法
     * @param object $mockObj
     */
    public function mockByObject($mockObj) {
        $newClass = new ReflectionClass($mockObj);
        $mockMethods = $newClass->getMethods();
        foreach ($mockMethods as $mockMethod) {
            $methodName = $mockMethod->getName();
            if (isset($this->methods[$methodName])) {
                // $mockMethod->isConstructor() || $mockMethod->isDeprecated()
                $this->mock($methodName, $mockMethod->getClosure($mockObj), $mockMethod->getModifiers());
            }
        }
    }

    /**
     * mock一个方法
     * @param string $methodName
     * @param Closure $mockMethod
     * @param int $modifiers
     */
    public function mock($methodName, Closure $mockMethod, $modifiers = null) {
        if (!isset($this->methods[$methodName])) {
            throw new \RuntimeException("$methodName not exist");
        }
        $this->backupMethods[$methodName] = true;

        // way1
        uopz_backup($this->className, $methodName);
        uopz_function($this->className, $methodName, $mockMethod, $modifiers);

        // way2 这种方式可以在mock之后调用原方法
        // uopz_rename($this->className, $methodName, "$methodName\0");
        // uopz_function($this->className, $methodName, $mockMethod, $modifiers);
    }

    public function unMock($someMethod = null) {
        if ($someMethod === null) {
            foreach ($this->backupMethods as $methodName) {
                // way1
                uopz_restore($this->className, $methodName);

                // way2
                // uopz_delete($this->className, $methodName);
                // uopz_rename($this->className, "$methodName\0", $methodName);
                unset($this->backupMethods[$methodName]);
            }
        } else if(isset($this->backupMethods[$someMethod])) {
            // way1
            uopz_restore($this->className, $someMethod);

            // way2
            // uopz_delete($this->className, $someMethod);
            // uopz_rename($this->className, "$someMethod\0", $someMethod);
            unset($this->backupMethods[$someMethod]);
        }
    }

    public function __destruct() {
        // 有概率异常 Allowed memory size of 134217728 bytes exhausted
        // $this->unMock();
    }
}