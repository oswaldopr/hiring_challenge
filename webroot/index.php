<?php
header("Content-Type: application/json; charset=utf-8");

/**
 * Loading composer libraries
 */
require __DIR__ . "/../vendor/autoload.php";

/**
 * Autoloading classes
 */
require __DIR__ . "/../autoloaders.php";

/**
 * Loading environment
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . "/../");
$dotenv->load();

/**
 * Loading configuration
 */
$redisHost = getenv("REDIS_HOST");
$redisPort = getenv("REDIS_PORT");
$allowedDomains = explode(",", getenv("ALLOWED_DOMAINS"));
$allowBlankReferrer = getenv("ALLOW_BLANK_REFERRER") || false;
$sessionHash = $_COOKIE["app"];

/**
 * Creating an object for responses
 */
$response = new Response();

/**
 * Creating an object for request (user's friends list)
 */
$request = new Request($response);

/**
 * Validating configuration, CORS ¿? and session
 */
if(!$request->validate($redisHost, $redisPort, $allowedDomains, $allowBlankReferrer, $sessionHash))
    exit;

try {
    /**
     * Creating the connection and session Redis
     */
    if(!$request->loadRedis())
        exit;

    /**
     * Getting friends list
     */
    $friendsList = $request->getFriendsList();
    if($friendsList === false)
        exit;

    /**
     * Showing friends list
     */
    $response->responseOK($friendsList->toArray());
}
catch (Exception $exception) {
    $response->responseInternalServerError("Unknown exception: " . $exception->getMessage());
}
?>