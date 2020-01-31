<?php

// Person class

include_once('../classes/transactionClass.php');

//$persons = $persons_object->getPersons($person_id, $no_of_persons);

//$body_data = json_encode(file_get_contents('php://input'));

$object = new stdClass();
$object->name = $_POST['name'];
$object->amount = $_POST['amount'];
$newTransaction = new person();
$newTransaction->makeTransaction($object);

if($object == true)
{
    
} elseif($object == false) {

}



?>






