<?php

class DeleteLocation extends Microservice
{

    public function execute()
    {
        $inputObject = new Request();
        $sessionObject = new Session();

        $returnData = ['code' => '001', 'result' => 'failure', 'data' => null];

        if ($id = $inputObject->issetGet('id', true, false)) {

            $locationObject = new Table('locations');

            $deleteResult = $locationObject->delete('id = "' . $id . '"');

            if ($deleteResult['status'] == 'success') {
                $returnData = ['code' => '010', 'result' => 'success'];
            } else {
                $returnData['code'] = '011';
            }
        }

        return $returnData;
    }
}
