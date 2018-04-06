<?php

class ValidateJWT extends Microservice
{

    public function execute()
    {
        $sessionObject = new Session();
        return ['code' => '009', 'result' => 'success', 'data' => $sessionObject->getSessionDetails()];
    }
}
