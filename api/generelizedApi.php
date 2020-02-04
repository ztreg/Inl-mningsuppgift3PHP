<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../classes/personClass.php');
include_once('../classes/transactionClass.php');

// Get request method. (GET, POST etc).
$request_method = strtolower($_SERVER['REQUEST_METHOD']);

$response = [
    'info' => null,
    'results' => null
];

if (empty($request_method)) {
    http_response_code(400);
} else {
    // Setup router.
    switch ($request_method) {
        // Create record.
        case 'post':
            $object = new stdClass();
            $object->fromName = filter_input(INPUT_POST, 'fromName', FILTER_SANITIZE_STRING);
            $object->toName = filter_input(INPUT_POST, 'toName', FILTER_SANITIZE_STRING);
            $object->moneyAmount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);
            $object->paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);
            
            $newTransaction = new transactionClass(new DB());
            if ($newTransaction->makeTransaction($object)) {
                http_response_code(201);
                $response['results'] = $object;
                $response['info']['message'] = "Transaction sucess!";
            } else {
                http_response_code(503);
                $response['info']['message'] = "Couldn't make transaction!";
            }
            break;

        // Everything else: GET.
        default:
        $getPeople = new personClass(new DB());
        $getTransactionInfo = new transactionClass(new DB());

            if ($getPeople->getPersons() && $getTransactionInfo->getTimestamps(10)){
                $persons = $getPeople->getPersons();
                $timeStamps = $getTransactionInfo->getTimestamps(10);
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
    }
}
echo json_encode($response);


