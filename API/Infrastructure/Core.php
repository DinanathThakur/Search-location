<?php

// Denying direct access to this file, have to call from index.php
defined('INFRASTRUCTURE_PATH') or exit('No direct script access allowed');

include_once INFRASTRUCTURE_PATH . 'Microservice.php';
include_once INFRASTRUCTURE_PATH . 'Request.php';
include_once INFRASTRUCTURE_PATH . 'Database' . DIRECTORY_SEPARATOR . 'DB.php';
include_once INFRASTRUCTURE_PATH . 'Database' . DIRECTORY_SEPARATOR . 'Table.php';
require_once INFRASTRUCTURE_PATH . 'Assets' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
include_once INFRASTRUCTURE_PATH . 'Session.php';

class Core
{

    public $apiName = null;
    private $microserviceConfig = null;
    private $apiConfig = null;
    private $headers = null;
    private $jwt = null;

    public function __construct()
    {
        $apiName = isset($_GET['apiName']) ? $_GET['apiName'] : null;

        $this->headers = getallheaders();

        if ($apiName) {

            $this->apiName = $apiName;

            $apiFilePath = MICROSERVICE_PATH . $apiName . '.php';

            $microserviceConfigFilePath = MICROSERVICE_PATH . 'config.json';

            if (file_exists($microserviceConfigFilePath)) {

                $this->microserviceConfig = $config = json_decode(file_get_contents($microserviceConfigFilePath), true);

                if (file_exists($apiFilePath) && isset($config['microservice'][$apiName])) {

                    $this->apiConfig = $apiConfig = $config['microservice'][$apiName];

                    if ($this->verifyJWT()) {
                        include_once $apiFilePath;

                        $apiObject = new $apiName();

                        $this->setResponse($apiObject->execute());
                    } else {
                        $this->throughError('Invalid header', 401);
                    }

                } else {
                    $this->throughError('Invalid API call', 404);
                }
            } else {
                $this->throughError('Config file missing', 404);
            }
        } else {
            $this->throughError('Invalid API call', 404);
        }

    }

    private function verifyJWT()
    {

        $returnResult = true;
        if (isset($this->apiConfig['JWTCheck']) && $this->apiConfig['JWTCheck']) {
            if (isset($this->headers['jwt']) && $this->headers['jwt'] != '') {

                $sessionObject = new Session($this->headers['jwt']);

                $returnResult = $sessionObject->checkJWT();
            } else {
                $this->throughError('JWT not passed', 401);
            }
        }

        return $returnResult;
    }

    private function throughError($message, $httpResponseCode = null)
    {
        if ($httpResponseCode) {
            $this->setHTTPResponseCode($httpResponseCode);
        }
        exit(json_encode(array('result' => 'failure', 'msg' => $message)));
    }

    private function setHTTPResponseCode($code)
    {
        http_response_code($code);
    }

    private function setHeader($header)
    {
        header($header);
    }

    private function setResponse($apiResponse)
    {
        $responseCodes = $this->microserviceConfig['responseCodes'];
        exit(json_encode([
            'code' => $apiResponse['code'],
            'message' => isset($responseCodes[$apiResponse['code']]) ? $responseCodes[$apiResponse['code']] : null,
            'result' => $apiResponse['result'],
            'data' => $apiResponse['data'],
        ]));
    }

    public function encodeJWT($data)
    {
        return $this->jwt = JWT::encode($data, $this->secretKey, $this->algo);
    }

    public function getJWT($jwt)
    {
        return $this->jwt;
    }

    public function isValidJWT()
    {
        $returnData = true;
        try {
            $returnData = JWT::decode($this->jwt, $this->secretKey, [$this->algo]);
        } catch (Exception $ex) {
            $returnData = false;
        }
        return $returnData;
    }

    public function getSessionDetails($key = null)
    {
        $sessionDetails = $this->isValidJWT();
        if ($key) {
            $sessionDetails = isset($sessionDetails->$key) ? $sessionDetails->$key : null;
        }
        return $sessionDetails;
    }

}
