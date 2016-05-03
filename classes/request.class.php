<?php
/**
 * Class to retrieve a user's friends list
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
class Request {

    //--constants of class (used as prefix)--//
    const FRIENDS_CACHE_PREFIX_KEY = "chat:friends:";
    const ONLINE_CACHE_PREFIX_KEY = "chat:online:";

    //--objects of class--//
    private $_response;
    private $_redis;

    //--properties of class--//
    private $_redisHost;
    private $_redisPort;
    private $_redisSession;
    private $_allowedDomains;
    private $_allowBlankReferrer;
    private $_sessionHash;
    private $_requestValidated;

    /**
     * Constructor of class
     * 
     * @param Response $response Object for responses
     */
    public function __construct(Response $response = null) {
        $this->_response = !empty($response) ? $response : new Response();
        $this->_redis = null;
        $this->_redisHost = null;
        $this->_redisPort = null;
        $this->_redisSession = null;
        $this->_allowedDomains = null;
        $this->_allowBlankReferrer = false;
        $this->_sessionHash = null;
        $this->_requestValidated = false;
    }

    /**
     * Destructor of class
     */
    public function __destruct() {
        unset($this->_response);
        unset($this->_redis);
    }

    //--begin setters & getters--//
    /**
     * Sets the Redis host
     * 
     * @param string $redisHost
     * @return void
     */
    private function _setRedisHost($redisHost) {
        if(!is_string($redisHost)) {
            $this->_response->responseInternalServerError("Bad format for Redis host.");
            return;
        }
        $this->_redisHost = $redisHost;
    }

    /**
     * Gets the Redis host
     * 
     * @return string
     */
    private function _getRedisHost() {
        return $this->_redisHost;
    }

    /**
     * Checks if the Redis host is empty
     * 
     * @return bool
     */
    private function _isEmptyRedisHost() {
        return empty($this->_redisHost);
    }

    /**
     * Sets the Redis port
     * 
     * @param string $redisPort
     * @return void
     */
    private function _setRedisPort($redisPort) {
        if(!is_string($redisPort)) {
            $this->_response->responseInternalServerError("Bad format for Redis port.");
            return;
        }
        $this->_redisPort = $redisPort;
    }

    /**
     * Gets the Redis port
     * 
     * @return string
     */
    private function _getRedisPort() {
        return $this->_redisPort;
    }

    /**
     * Checks if the Redis port is empty
     * 
     * @return bool
     */
    private function _isEmptyRedisPort() {
        return empty($this->_redisPort);
    }

    /**
     * Sets the Redis session
     * 
     * @param mixed $redisSession
     * @return void
     */
    private function _setRedisSession($redisSession) {
        $this->_redisSession = $redisSession;
    }

    /**
     * Gets the Redis session
     * 
     * @return mixed
     */
    private function _getRedisSession() {
        return $this->_redisSession;
    }

    /**
     * Checks if the Redis session is empty
     * 
     * @return bool
     */
    private function _isEmptyRedisSession() {
        return empty($this->_redisSession);
    }

    /**
     * Sets an array with allowed domains
     * 
     * @param array $allowedDomains
     * @return void
     */
    private function _setAllowedDomains($allowedDomains) {
        if(!is_array($allowedDomains)) {
            $this->_response->responseInternalServerError("Bad format for allowed domains.");
            return;
        }
        $this->_allowedDomains = $allowedDomains;
    }

    /**
     * Gets an array with allowed domains
     * 
     * @return array
     */
    private function _getAllowedDomains() {
        return $this->_allowedDomains;
    }

    /**
     * Checks if the array with allowed domains is empty
     * 
     * @return bool
     */
    private function _isEmptyAllowedDomains() {
        return empty($this->_allowedDomains);
    }

    /**
     * Sets if blank referrer is permitted
     * 
     * @param mixed $allowBlankReferrer
     * @return void
     */
    private function _setAllowBlankReferrer($allowBlankReferrer) {
        $this->_allowBlankReferrer = $allowBlankReferrer || false;
    }

    /**
     * Gets if blank referrer is permitted
     * 
     * @return bool
     */
    private function _getAllowBlankReferrer() {
        return $this->_allowBlankReferrer;
    }

    /**
     * Sets the value for session hash
     * 
     * @param mixed $sessionHash
     * @return void
     */
    private function _setSessionHash($sessionHash) {
        if(!is_string($sessionHash)) {
            $this->_response->responseInternalServerError("Bad format for session hash.");
            return;
        }
        $this->_sessionHash = $sessionHash;
    }

    /**
     * Gets the value for session hash
     * 
     * @return string
     */
    private function _getSessionHash() {
        return $this->_sessionHash;
    }

    /**
     * Checks if the session hash is empty
     * 
     * @return bool
     */
    private function _isEmptySessionHash() {
        return empty($this->_sessionHash);
    }

    /**
     * Sets if the request was validated
     * 
     * @param bool $requestValidated
     * @return void
     */
    private function _setRequestValidated($requestValidated) {
        $this->_requestValidated = $requestValidated || false;
    }

    /**
     * Gets if the request was validated
     * 
     * @return bool
     */
    private function _isRequestValidated() {
        return $this->_requestValidated;
    }
    //--end setters & getters--//

    /**
     * Validates configuration, access control and session
     * 
     * @param string $host Redis host
     * @param string $port Redis port
     * @param array $domains Allowed domains
     * @param bool $blankReferrer Allow blank referrer
     * @param string $session Session hash
     * @return bool
     */
    public function validate($host = "", $port = "", array $domains = null, $blankReferrer = false, $session = "") {
        //--in this point, if $this->_requestValidated is true the old data should be cleaned--//
        //--here could be implemented the code for new validation data (disconnect Redis, etc)--//
        $this->_setRequestValidated(false);

        //--server configuration--//
        $this->_setRedisHost($host);
        $this->_setRedisPort($port);
        if(!$this->checkServerConfiguration())
            return false;

        //--access control--//
        $this->_setAllowedDomains($domains);
        $this->_setAllowBlankReferrer($blankReferrer);
        if(!$this->checkAccessControl())
            return false;

        //--session--//
        $this->_setSessionHash($session);
        if(!$this->checkSession())
            return false;

        $this->_setRequestValidated(true);
        return true;
    }

    /**
     * Checks the configuration of the server
     * 
     * @return bool
     */
    private function checkServerConfiguration() {
        if($this->_isEmptyRedisHost() || $this->_isEmptyRedisPort()) {
            $this->_response->responseInternalServerError("Server error, invalid configuration.");
            return false;
        }
        return true;
    }

    /**
     * Checks if the access is permitted
     * 
     * @return bool
     */
    private function checkAccessControl() {
        $httpOrigin = !empty($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : null;
        if($this->_getAllowBlankReferrer() || in_array($httpOrigin, $this->_getAllowedDomains())) {
            @header("Access-Control-Allow-Credentials: true");
            if(!empty($httpOrigin))
                @header("Access-Control-Allow-Origin: $httpOrigin");
            return true;
        }
        $this->_response->responseNotAuthorized("Not a valid origin.");
        return false;
    }

    /**
     * Checks if is a valid session
     * 
     * @return bool
     */
    private function checkSession() {
        if($this->_isEmptySessionHash()) {
            $this->_response->responseNotAuthorized();
            return false;
        }
        return true;
    }

    /**
     * Load the Redis connection and sets the session if it was satisfactory
     * 
     * @return bool
     */
    public function loadRedis() {
        if(!$this->_isRequestValidated()) {
            $this->_response->responseInternalServerError("Server error, invalid configuration.");
            return false;
        }

        //--Redis connection (could be validated too if already connection exists)--//
        $this->_redis = new Redis();
        $this->_redis->connect($this->_getRedisHost(), $this->_getRedisPort());
        if(!$this->_redis->isConnected()) {
            $this->_response->responseInternalServerError();
            return false;
        }

        //--set Redis serialization strategy--//
        $this->_redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        //--set Redis session--//
        $this->_setRedisSession($this->_redis->get("PHPREDIS_SESSION:" . $this->_getSessionHash()));

        //--don't set cookie--//
        header_remove("Set-Cookie");

        return true;
    }

    /**
     * Returns the user's friends list, otherwise returns false
     * 
     * @return mixed
     */
    public function getFriendsList() {
        $session = $this->_getRedisSession();
        if(empty($session["default"]["id"])) {
            $this->_response->responseNotFound();
            return false;
        }

        $friendsList = $this->_redis->get(self::FRIENDS_CACHE_PREFIX_KEY . $session["default"]["id"]);
        if(empty($friendsList)) {
            $this->_response->responseOK([]);
            return false;
        }

        //--in order to be "blazing fast" I did make a few changes in FriendsList::getUserIds()--//
        //--I think that could it work... I mean, be some more fast--//
        $friendsUser = $friendsList->getUserIds(self::ONLINE_CACHE_PREFIX_KEY);
        if(!empty($friendsUser)) {
            //--multi-get for faster operations--//
            $result = $this->_redis->mget($friendsUser["keys"]);

            $onlineUsers = array_filter(array_combine($friendsUser["ids"], $result));
            if(!empty($onlineUsers))
                $friendsList->setOnline($onlineUsers);
        }

        return $friendsList;
    }
}
?>