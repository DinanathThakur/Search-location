<?php

class Register extends Microservice
{

    public function execute()
    {
        $inputObject = new Request();
        $sessionObject = new Session();

        $returnData = ['code' => '001', 'result' => 'failure', 'data' => null];

        if (($firstName = $inputObject->issetGet('firstName', true, false)) &&
            ($lastName = $inputObject->issetGet('lastName', true, false)) &&
            ($email = $inputObject->issetGet('email', true, false)) &&
            ($password = $inputObject->issetGet('password', true, false))) {

            $userObject = new Table('users');

            $userDetails = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => md5($password),
            ];

            $userResponse = $userObject->insert($userDetails);

            if ($userResponse['status'] == 'success') {

                $jwt = $sessionObject->encodeJWT([
                    'userID' => $userResponse['data'],
                    'emailID' => $email,
                    'name' => $firstName . ' ' . $lastName,
                    'time' => time(),
                ]);
                $this->setHeader("jwt: $jwt");

                $returnData = ['code' => '002', 'result' => 'success'];
            } else {
                $returnData['code'] = '003';
            }
            $returnData['data'] = $userResponse['data'];
        }

        return $returnData;
    }
}
