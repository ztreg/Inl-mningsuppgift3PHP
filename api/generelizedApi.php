<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get request method. (GET, POST etc).
$request_method = strtolower($_SERVER['REQUEST_METHOD']);

$response = [
    'info' => null,
    'results' => null
];

try {
    if (empty('fromName') && empty('toName') && empty('amount') && empty('paymentMethod')) {
        http_response_code(400);
    } else {
        try {
            // Setup router.
            switch ($request_method) {
                    // Create record.

                case 'post':
                    include_once('../classes/transactionClass.php');
                    $object = new stdClass();
                    $object->fromName = filter_input(INPUT_POST, 'fromName', FILTER_SANITIZE_STRING);
                    $object->toName = filter_input(INPUT_POST, 'toName', FILTER_SANITIZE_STRING);
                    $object->moneyAmount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);
                    if ($object->moneyAmount <= 0) {      
                        throw new \Exception("You cant send 0 or less");
                    }
                    $object->paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);

                    $newTransaction = new transactionClass(new MySQLDB());
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
                    include_once('../classes/personClass.php');
                    include_once('../classes/transactionClass.php');
                    $getPeople = new personClass(new MySQLDB());
                    $getTransactionInfo = new transactionClass(new MySQLDB());

                    if ($getPeople->getPersons() && $getTransactionInfo->getTimestamps(10)) {
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
        } catch (Exception $e) {
            echo  $e->getMessage(), "\n";
        }
    }
} catch (Exception $e) {
    echo 'Your post request was invalid, try posting with the required names & values. ',  $e->getMessage(), "\n";
}
echo json_encode($response);
