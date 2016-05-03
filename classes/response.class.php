<?php
/**
 * Class for responses (to browser)
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
class Response {

    //--constants of class--//
    const CODE_OK = 200;
    const CODE_NOT_AUTHORIZED = 403;
    const CODE_NOT_FOUND = 404;
    const CODE_INTERNAL_ERROR_SERVER = 500;
    const STATUS_RESPONSE_OK = 1;
    const STATUS_RESPONSE_FAIL = -1;
    const STATUS_RESPONSE_STOP = 0;

    //--properties of class--//
    private $_httpCode;
    private $_response;
    private $_statusResponse;

    /**
     * Constructor of class
     */
    public function __construct() {
        $this->_httpCode = null;
        $this->_response = null;
        $this->_statusResponse = null;
    }

    //--begin setters & getters--//
    /**
     * Sets the value for HTTP Code
     * 
     * @param int $httpCode
     * @return void
     */
    private function _setHttpCode($httpCode) {
        if(!is_int($httpCode)) {
            $this->responseInternalServerError("Invalid HTTP Code.");
            return;
        }
        $this->_httpCode = $httpCode;
    }

    /**
     * Gets the value for HTTP Code
     * 
     * @return int
     */
    private function _getHttpCode() {
        return $this->_httpCode;
    }

    /**
     * Checks if the HTTP Code is empty
     * 
     * @return bool
     */
    private function _isEmptyHttpCode() {
        return empty($this->_httpCode);
    }

    /**
     * Sets the data to show for a response (JSON format)
     * 
     * @param array $response
     * @return void
     */
    private function _setResponse($response) {
        if(!is_array($response)) {
            $this->responseInternalServerError("Bad format for response.");
            return;
        }
        $this->_response = json_encode($response);
    }

    /**
     * Sets the message for an error response (JSON format)
     * 
     * @param string $message
     * @return void
     */
    private function _setResponseError($message) {
        $this->_response = json_encode(["error" => true, "message" => $message]);
    }

    /**
     * Gets the data for a response (JSON format)
     * 
     * @return string
     */
    private function _getResponse() {
        return $this->_response;
    }

    /**
     * Checks if the data are empty
     * 
     * @return bool
     */
    private function _isEmptyResponse() {
        return empty($this->_response);
    }

    /**
     * Sets the status for a response
     * 
     * @param int $statusResponse
     * @return void
     */
    private function _setStatusResponse($statusResponse) {
        $this->_statusResponse = $statusResponse;
    }

    /**
     * Gets the status for a response
     * 
     * @return int
     */
    private function _getStatusResponse() {
        return $this->_statusResponse;
    }
    //--end setters & getters--//

    /**
     * Sends a response (to browser) and returns HTTP Code
     * 
     * @return int
     */
    private function createResponse() {
        $statusResponse = $this->_getStatusResponse();
        if($statusResponse == self::STATUS_RESPONSE_STOP)
            return $this->_getHttpCode();
        elseif($statusResponse == self::STATUS_RESPONSE_FAIL)
            $this->_setStatusResponse(self::STATUS_RESPONSE_STOP);
        http_response_code($this->_getHttpCode());
        echo $this->_getResponse();
        return $this->_getHttpCode();
    }

    /**
     * Prepares and sends a response for a valid request; it returns HTTP Code
     * 
     * @param array $data Data to show
     * @return int
     */
    public function responseOK(array $data) {
        $this->_setStatusResponse(self::STATUS_RESPONSE_OK);
        $this->_setHttpCode(self::CODE_OK);
        $this->_setResponse($data);
        return $this->createResponse();
    }

    /**
     * Prepares and sends a response for a not authorized request due to a missing cookie,
     * an invalid session or a bad referrer domain; it returns HTTP Code
     * 
     * @param string $message Error message
     * @return int
     */
    public function responseNotAuthorized($message = "Not a valid session.") {
        $this->_setStatusResponse(self::STATUS_RESPONSE_FAIL);
        $this->_setHttpCode(self::CODE_NOT_AUTHORIZED);
        $this->_setResponseError($message);
        return $this->createResponse();
    }

    /**
     * Prepares and sends a response for a friends list not available; it returns HTTP Code
     * 
     * @param string $message Error message
     * @return int
     */
    public function responseNotFound($message = "Friends list not available.") {
        $this->_setStatusResponse(self::STATUS_RESPONSE_FAIL);
        $this->_setHttpCode(self::CODE_NOT_FOUND);
        $this->_setResponseError($message);
        return $this->createResponse();
    }

    /**
     * Prepares and sends a response for a bad app configuration or because Redis is down; it returns HTTP Code
     * 
     * @param string $message Error message
     * @return int
     */
    public function responseInternalServerError($message = "Server error, can't connect.") {
        $this->_setStatusResponse(self::STATUS_RESPONSE_FAIL);
        $this->_setHttpCode(self::CODE_INTERNAL_ERROR_SERVER);
        $this->_setResponseError($message);
        return $this->createResponse();
    }
}
?>