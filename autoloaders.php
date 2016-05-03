<?php
/**
 * Autoloader for app classes
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 * @param string $className Name of class to require
 */
function autoload_app_class($className) {
    autoload("classes", $className);
}

/**
 * Autoloader for test classes
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 * @param string $className Name of class to require
 */
function autoload_test_class($className) {
    autoload("tester" . DIRECTORY_SEPARATOR . "classes", $className);
}

/**
 * Autoloader for test interfaces
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 * @param string $interfaceName Name of interface to require
 */
function autoload_test_interface($interfaceName) {
    autoload("tester" . DIRECTORY_SEPARATOR . "classes", $interfaceName, true);
}

/**
 * "Private" function to autoload classes/interfaces, not register with spl_autoload_register()
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 * @param string $directory Path to search the class file to require
 * @param string $className Name of class/interface to require
 */
function autoload($directory, $className, $interface = false) {
    $type = !$interface ? ".class.php" : ".iclass.php";
    $filename = __DIR__ . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . strtolower($className) . $type;
    if(file_exists($filename))
        require_once $filename;
}

/**
 * Registering autoload functions
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
spl_autoload_register("autoload_app_class");
spl_autoload_register("autoload_test_class");
spl_autoload_register("autoload_test_interface");
?>