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

//$body_data = json_encode(file_get_contents('php://input'));
//$d1 = new Datetime();
//$time = $d1->date;

$object = new stdClass();
$object->fromName = $_POST['fromName'];
$object->toName = $_POST['toName'];
$object->moneyAmount = $_POST['amount'];
//$object->timeStamp = $time;

$newTransaction = new person();
$response = [
    'results' => null
];
if ($newTransaction->makeTransaction($object)) 
{
   
    // Set a suitable response code.
    http_response_code(201);
    $_SESSION['info'] = "Transaction sucess!";
    // Set a readable message.
    $response['info']['message'] = "Transaction sucess!";

    // Add the newly created product to results.
    $response['results'] = $object;
} else {
    // Set a suitable response code.
    http_response_code(500);
    $_SESSION['info'] = "Transaction failed!";
    // Set a readable message.
    $response['info']['message'] = "Couldn't make transaction!";
}

// Format response.
// Same as last one.
echo json_encode($response);

?>






