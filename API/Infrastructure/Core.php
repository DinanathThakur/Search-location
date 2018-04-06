<?php

// Denying direct access to this file, have to call from index.php
defined('INFRASTRUCTURE_PATH') or exit('No direct script access allowed');

include_once INFRASTRUCTURE_PATH . 'Microservice.php';

include_once INFRASTRUCTURE_PATH . 'Core' . DIRECTORY_SEPARATOR . 'Response' . DIRECTORY_SEPARATOR . 'Response.php';
include_once INFRASTRUCTURE_PATH . 'Core' . DIRECTORY_SEPARATOR . 'Input' . DIRECTORY_SEPARATOR . 'Input.php';
include_once INFRASTRUCTURE_PATH . 'Helper' . DIRECTORY_SEPARATOR . 'BaseHelper.php';

include_once INFRASTRUCTURE_PATH . 'Interface' . DIRECTORY_SEPARATOR . 'IInfrastructure.php';
include_once INFRASTRUCTURE_PATH . 'InfrastructureManager.php';

include_once INFRASTRUCTURE_PATH . 'Interface' . DIRECTORY_SEPARATOR . 'IPersistence.php';
include_once INFRASTRUCTURE_PATH . 'Interface' . DIRECTORY_SEPARATOR . 'IPersistenceDataSource.php';
include_once INFRASTRUCTURE_PATH . 'Components' . DIRECTORY_SEPARATOR . 'PersistenceManager.php';

include_once INFRASTRUCTURE_PATH . 'Core' . DIRECTORY_SEPARATOR . 'Router.php';

include_once INFRASTRUCTURE_PATH . 'Interface' . DIRECTORY_SEPARATOR . 'IObjectHelper.php';
include_once INFRASTRUCTURE_PATH . 'Object' . DIRECTORY_SEPARATOR . 'DBObject.php';
include_once INFRASTRUCTURE_PATH . 'Object' . DIRECTORY_SEPARATOR . 'ViewObject.php';
include_once INFRASTRUCTURE_PATH . 'Object' . DIRECTORY_SEPARATOR . 'ObjectHelper.php';
include_once INFRASTRUCTURE_PATH . 'Object' . DIRECTORY_SEPARATOR . 'ViewObjectRecord.php';

require_once INFRASTRUCTURE_PATH . 'Assets' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class Core
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');

        $router = new EI\Infrastructure\Router();

        $productName = $router->getProductName();
        $microserviceName = $router->getMicroserviceName();
        $apiName = $router->getApiName();

        $microservicePath = MICROSERVICE_PATH . $microserviceName . DIRECTORY_SEPARATOR;

        if (!is_dir($microservicePath)) {
            $this->throughError('Invalid Microservice', 404);
        }

        // Step 2 : Include and create Microservice and pass the parameters

        if (file_exists($router->getApiFilePath())) {
            include_once $router->getApiFilePath();
        } else {
            $this->throughError('Invalid API call', 404);
        }

        $this->updateInputParameter();

        if (!empty(($_tempInput = file_get_contents('php://input')))) {
            if ($this->isJson($_tempInput)) {
                $_POST = array_merge($_POST, json_decode($_tempInput, true));
            }
        }

        $data = array(
            'productName' => $productName,
            'parameters' => ['post' => $_POST, 'get' => $_GET, 'file' => $_FILES],
        );

        $apiClassName = $router->getApiClassName();
        $apiClassNameCreate = class_exists($apiClassName[0]) ? $apiClassName[0] : $apiClassName[1];
        new $apiClassNameCreate($microserviceName, $apiName, $data);
    }

    public function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context)
    {
        // print_r([$error_level, $error_message, $error_file, $error_line, $error_context]);
        $error = "lvl: " . $error_level . " | msg:" . $error_message . " | file:" . $error_file . " | ln:" . $error_line;
        switch ($error_level) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_PARSE:
                $this->mylog($error, "fatal");
                break;
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $this->mylog($error, "error");
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                $this->mylog($error, "warn");
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $this->mylog($error, "info");
                break;
            case E_STRICT:
                $this->mylog($error, "debug");
                break;
            default:
                $this->mylog($error, "warn");
        }
    }

    public function shutdownHandler()
    {
        $lasterror = error_get_last();
        switch ($lasterror['type']) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_PARSE:
                $error = "[SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];
                $this->mylog($error, "fatal");
        }
    }

    private function mylog($error, $errlvl)
    {
        // print_r([$error, $errlvl]);
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Through Error
     *
     * Terminates the program by througing error message
     *
     * @param string $message to display to the caller
     * @return json result as failure and msg as mentioned by user
     */
    private function throughError($message, $httpResponseCode = null)
    {
        if ($httpResponseCode) {
            $this->setHTTPResponseCode($httpResponseCode);
        }
        exit(json_encode(array('result' => 'failure', 'msg' => $message)));
    }

    private function updateInputParameter()
    {
        $_POST = $this->trimKey($_POST);
        $_GET = $this->trimKey($_GET);
    }

    private function trimKey($arrayData)
    {
        $returnArray = array();
        foreach ($arrayData as $key => $value) {
            $returnArray[rtrim($key, '_')] = $value;
        }
        return $returnArray;
    }

    private function setHTTPResponseCode($code)
    {
        http_response_code($code);
    }
}
