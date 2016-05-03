<?php
/**
 * Interface for class Tester
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
interface iTester {

    /**
     * Gets a string literal to the value returned by a method
     * 
     * @param string $methodName Name of method
     * @return string
     */

    public function _getReturnValue($methodName);

    /**
     * Gets a string used to construct built-in functions is_xxxx()
     * 
     * @param string $methodName Name of method
     * @return string
     */
    public function _getReturnValueType($methodName);
}
?>