<?php
/**
 * Class to test "class Request"
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
class RequestTest extends Tester implements iTester {

    /**
     * Constructor of class
     */
    public function __construct() {
        parent::__construct("Request");
        $this->setAssertOptions();
    }

    //--begin setters & getters--//
    /**
     * Gets a string literal to the value returned by a method
     * Method implemented from interface iTester
     * 
     * @param string $methodName Name of method
     * @return string
     */
    public function _getReturnValue($methodName) {
        switch(strtolower($methodName)) {
            case "validate":
            case "loadredis":
                return "Boolean: true success, false otherwise";
                break;
            case "getfriendslist":
                return "Mixed: Friends list: array on success, false otherwise";
                break;
            default:
                return "";
        }
    }

    /**
     * Gets a string used to construct built-in functions is_xxxx()
     * Method implemented from interface iTester
     * 
     * @param string $methodName Name of method
     * @return string
     */
    public function _getReturnValueType($methodName) {
        switch(strtolower($methodName)) {
            case "validate":
            case "loadredis":
            case "getfriendslist":
                return "bool";
                break;
            default:
                return "";
        }
    }
    //--end setters & getters--//

    /**
     * Tests method Request::validate()
     * 
     * @return void
     */
    public function testValidate() {
        $methodName = "validate";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }

    /**
     * Tests method Request::loadRedis()
     * 
     * @return void
     */
    public function testLoadRedis() {
        $methodName = "loadRedis";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }

    /**
     * Tests method Request::getFriendsList()
     * 
     * @return void
     */
    public function testGetFriendsList() {
        $methodName = "getFriendsList";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }
}
?>