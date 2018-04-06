<?php

defined('INFRASTRUCTURE_PATH') or exit('No direct script access allowed');

class Microservice
{

    public function setHeader($header)
    {
        header($header);
    }

    public function setResponse($data = null)
    {
        exit(json_encode($data));
    }

}
