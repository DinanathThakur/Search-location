<?php

use \Firebase\JWT\JWT;

class Session
{

    protected $jwt = null;
    protected $secretKey = 'jwt-secret-key';
    protected $algo = 'HS512';

    public function __construct()
    {
        $headers = getallheaders();
        $this->jwt = isset($headers['jwt']) && $headers['jwt'] != '' ? $headers['jwt'] : null;
    }

    public function encodeJWT($data)
    {
        return $this->jwt = JWT::encode($data, $this->secretKey, $this->algo);
    }

    public function getJWT($jwt)
    {
        return $this->jwt;
    }

    public function checkJWT()
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
        $sessionDetails = $this->checkJWT();
        if ($key) {
            $sessionDetails = isset($sessionDetails->$key) ? $sessionDetails->$key : null;
        }
        return $sessionDetails;
    }

}
