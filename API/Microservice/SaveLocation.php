<?php

class SaveLocation extends Microservice
{

    public function execute()
    {
        $inputObject = new Request();
        $sessionObject = new Session();

        $returnData = ['code' => '001', 'result' => 'failure', 'data' => null];

        if (($name = $inputObject->issetGet('name', true, false)) &&
            ($description = $inputObject->issetGet('description', true, false)) &&
            ($lat = $inputObject->issetGet('lat', true, false)) &&
            ($lng = $inputObject->issetGet('lng', true, false))) {

            $locationObject = new Table('locations');

            $locationDetails = [
                'userID' => $sessionObject->getSessionDetails('userID'),
                'name' => $name,
                'description' => $description,
                'lat' => $lat,
                'lng' => $lng,
            ];

            $insertResult = $locationObject->insert($locationDetails);

            if ($insertResult['status'] == 'success') {
                $returnData = ['code' => '006', 'result' => 'success'];
            } else {
                $returnData['code'] = '007';
            }
            $returnData['data'] = $insertResult['data'];
        }

        return $returnData;
    }
}
