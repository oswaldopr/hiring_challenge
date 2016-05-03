<?php
require __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "autoloaders.php";

/**
 * Validating class
 */
$className = "";
if(!empty($argv[1]))
    $className = $argv[1];
elseif(!empty($_GET["class"]))
    $className = $_GET["class"];
if(empty($className)) {
    include "about_testfiles.php";
    exit;
}
if(!class_exists($className)) {
    echo "Aborting: Class not exists!" . PHP_EOL;
    exit;
}
echo "Class '$className' found..." . PHP_EOL;

/**
 * Validating method
 */
$methodName = !empty($_GET["method"]) ? $_GET["method"] : "";
if(empty($methodName) && !empty($argv[2]) && $argv[2] != "-f")
    $methodName = $argv[2];
$class = new ReflectionClass($className);
$object = $class->newInstance();
if(!empty($methodName) && !method_exists($object, $methodName)) {
    echo "Aborting: Method not exists!" . PHP_EOL;
    unset($object);
    unset($class);
    exit;
}
unset($object);
echo (empty($methodName) ? "No method..." : "Method '$methodName' found...") . PHP_EOL;

/**
 * Validating overwrite
 */
$forceOverwrite = isset($_GET["force"]);
if(empty($forceOverwrite)) {
    $forceOverwrite = (!empty($argv[2]) && $argv[2] == "-f");
    if(empty($forceOverwrite))
        $forceOverwrite = (!empty($argv[3]) && $argv[3] == "-f");
}
echo ($forceOverwrite ? "Overwrite file(s)..." : "No overwrite...") . PHP_EOL;

/**
 * Creating test file(s)
 */
echo PHP_EOL;
$testFile = new Tester($class);
$methodsList = (empty($methodName)) ? $class->getMethods() : [$class->getMethod($methodName)];
foreach($methodsList as $method) {
    $testFile->_setMethod($method);
    $status = $testFile->createTestFile($forceOverwrite);
    $message = "Test file '" . $testFile->_getFileName() . "'";
    switch($status) {
        case File::FILE_CREATED:
        case File::FILE_OVERWRITTEN:
            echo "$message was created..." . PHP_EOL;
            break;
        case File::FILE_NOT_OVERWRITTEN:
            echo "$message already exists..." . PHP_EOL;
    }
}
unset($testFile);
unset($class);
?>