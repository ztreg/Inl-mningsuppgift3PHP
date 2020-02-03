<?php
// Allow any site to fetch this result.
header("Access-Control-Allow-Origin: *");

// Set header to let browser know to expect json instead of html.
header("Content-Type: application/json; charset=UTF-8");

// Setup POST to be the only acceptable way to contact this page.
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Person class
include_once('../classes/transactionClass.php');

//$persons = $persons_object->getPersons($person_id, $no_of_persons);

$object = new stdClass();
$object->fromName = filter_input(INPUT_POST, 'fromName', FILTER_SANITIZE_STRING);
$object->toName = filter_input(INPUT_POST, 'toName', FILTER_SANITIZE_STRING);
$object->moneyAmount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);
$object->paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);
//$object->timeStamp = $time;

$newTransaction = new person();
$response = [
    'results' => null
];
if ($newTransaction->makeTransaction($object)) {
    http_response_code(201);
    $_SESSION['info'] = "Transaction sucess!";
    $response['info']['message'] = "Transaction sucess!";
    $response['results'] = $object;
} else {
    http_response_code(500);
    $_SESSION['info'] = "Transaction failed!";
    $response['info']['message'] = "Couldn't make transaction!";
}


// Format response.
// Same as last one.
echo json_encode($response);