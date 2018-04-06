<?php

class GetAllLocation extends Microservice
{

    public function execute()
    {
        $inputObject = new Request();
        $sessionObject = new Session();

        $limit = $inputObject->issetGet('length', true, 10);

        $page = ($inputObject->isset('start', true) ? ($inputObject->get('start') / $limit) + 1 : 1);

        $options['where'] = 'userID = "' . $sessionObject->getSessionDetails('userID') . '"';
        $options['limit'] = (int)$limit;
        $options['offset'] = (int)(($page - 1) * $limit);

        $locationObject = new Table('locations');
        $locations = $locationObject->getAll($options)['data'];

        $recordsTotal = $filteredRecordCount = $locationObject->getCount($options['where'])['data'];

        $dataForTable = [];
        if (!empty($locations)) {
            foreach ($locations as $location) {
                $dataForTable[] = [
                    'name' => $location['name'],
                    'description' => $location['description'],
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                    'createdAt' => $location['createdAt'],
                    'action' => '<span class="btn btn-sm delete-location" data-id="' . $location['id'] . '"><i class="glyphicon glyphicon-trash"></i></span>',
                ];
            }
        }

        $response = ['recordsTotal' => $recordsTotal, 'recordsFiltered' => $filteredRecordCount, 'draw' => (int)$inputObject->get('draw'), 'data' => $dataForTable];

        $this->setResponse($response);
    }
}
