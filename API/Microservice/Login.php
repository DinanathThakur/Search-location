<?php

class Login extends Microservice
{

    public function execute()
    {
        $inputObject = new Request();
        $sessionObject = new Session();

        $returnData = ['code' => '001', 'result' => 'failure', 'data' => null];

        if (($email = $inputObject->issetGet('email', true, false)) &&
            ($password = $inputObject->issetGet('password', true, false))) {

            $userObject = new Table('users');

            $where = 'email = "' . $email . '" AND password = "' . md5($password) . '"';

            $userResponse = $userObject->get(['where' => $where]);

            if ($userResponse['status'] == 'success') {
                $userDetails = $userResponse['data'];

                $jwt = $sessionObject->encodeJWT([
                    'userID' => $userDetails['data'],
                    'emailID' => $userDetails['email'],
                    'name' => $userDetails['firstName'] . ' ' . $userDetails['lastName'],
                    'time' => time(),
                ]);
                $this->setHeader("jwt: $jwt");

                $returnData = ['code' => '004', 'result' => 'success'];
            } else {
                $returnData['code'] = '005';
            }
            $returnData['data'] = $userResponse['data'];
        }

        return $returnData;
    }
}
