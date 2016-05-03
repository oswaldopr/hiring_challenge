<?php
require __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "autoloaders.php";

/**
 * Validating classes (app and test)
 */
$className = "";
if(!empty($argv[1]))
    $className = $argv[1];
elseif(!empty($_GET["class"]))
    $className = $_GET["class"];
$className = strtolower($className);
if(empty($className)) {
    include "about_tester.php";
    exit;
}
elseif(!class_exists($className)) {
    echo "Aborting: Class not exists!" . PHP_EOL;
    exit;
}
elseif(!class_exists($className . "test")) {
    echo "Aborting: Test class not exists!" . PHP_EOL;
    exit;
}
$class = new ReflectionClass($className);
$testClass = new ReflectionClass($className . "test");
echo "Class '" . $class->getName() . "' found..." . PHP_EOL;

/**
 * Validating methods (app and test)
 */
$methodName = !empty($_GET["method"]) ? $_GET["method"] : "";
if(empty($methodName) && !empty($argv[2]) && $argv[2] != "-f")
    $methodName = $argv[2];
$methodName = strtolower($methodName);
if(!empty($methodName)) {
    if(!method_exists($class->newInstance(), $methodName)) {
        echo "Aborting: Method not exists!" . PHP_EOL;
        unset($class);
        unset($testClass);
        exit;
    }
    if(!method_exists($testClass->newInstance(), "test" . $methodName)) {
        echo "Aborting: Test method not exists!" . PHP_EOL;
        unset($testClass);
        unset($class);
        exit;
    }
    echo "Method '" . $class->getName() . "::$methodName()' found..." . PHP_EOL;
}
else
    echo "No method..." . PHP_EOL;
unset($class);

/**
 * Testing
 */
echo PHP_EOL;
$tester = new Tester($testClass);
$methodsList = (empty($methodName)) ? $testClass->getMethods() : [$testClass->getMethod("test" . $methodName)];
foreach($methodsList as $method) {
    $testMethod = (strncasecmp("test", $method->getName(), 4) === 0);
    if(!$testMethod || !$method->isPublic())
        continue;
    $method->invoke($testClass->newInstance());
}
unset($tester);
unset($testClass);
?>