<?php
/**
 * Class to test "class Response"
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
class ResponseTest extends Tester implements iTester {

    /**
     * Constructor of class
     */
    public function __construct() {
        parent::__construct("Response");
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
            case "responseok":
                return "Integer: HTTP Code: 200 on success, 500 otherwise";
                break;
            case "responsenotauthorized":
                return "Integer: HTTP Code: 403";
                break;
            case "responsenotfound":
                return "Integer: HTTP Code: 404";
                break;
            case "responseinternalservererror":
                return "Integer: HTTP Code: 500";
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
            case "responseok":
            case "responsenotauthorized":
            case "responsenotfound":
            case "responseinternalservererror":
                return "int";
                break;
            default:
                return "";
        }
    }
    //--end setters & getters--//

    /**
     * Tests method Response::responseOK()
     * 
     * @return void
     */
    public function testResponseOK() {
        $methodName = "responseOK";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }

    /**
     * Tests method Response::responseNotAuthorized()
     * 
     * @return void
     */
    public function testResponseNotAuthorized() {
        $methodName = "responseNotAuthorized";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }

    /**
     * Tests method Response::responseNotFound()
     * 
     * @return void
     */
    public function testResponseNotFound() {
        $methodName = "responseNotFound";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }

    /**
     * Tests method Response::responseInternalServerError()
     * 
     * @return void
     */
    public function testResponseInternalServerError() {
        $methodName = "responseInternalServerError";
        $this->testMethod($methodName, $this->_getReturnValue($methodName), $this->_getReturnValueType($methodName));
    }
}
?>