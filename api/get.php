<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../classes/transactionClass.php');

$getPeople = new person();

// Get persons.

if ($getPeople->getPersons() && $getPeople->getTimestamps(10)){
    $persons = $getPeople->getPersons();
    $timeStamps = $getPeople->getTimestamps(10);
    $response = [
        'info' => [
            'posts' => count($persons),
        ],
        'result' => $persons,
        'result2' => $timeStamps
    ];
    http_response_code(200);
   
    $response['info']['message'] = 'Everything was ok';
} else {
    http_response_code(404);

    $response['info']['message'] = "Couldn't find any records";
}

echo json_encode($response);


//Try catch i php, inte sql


