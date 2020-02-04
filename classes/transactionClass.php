<?php
include_once 'db.php';

class transactionClass
{
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db->getDB();
    }
    public function getTimestamps($limit)
    {
        // Setup query.
        $sql = 'SELECT * FROM timestamp ORDER BY timeStamp DESC';
        $parameters = null;

        $sql .= ' LIMIT ' . $limit;
        $statement = $this->db->prepare($sql);
        $statement->execute($parameters);

        // Setup array to contain persons.
        $timeStamps = [];

        // Fetch is faster than fetchall.
        while ($row = $statement->fetch()) {
            // Extract $row['personCode'] to $personCode etc.
            extract($row);

            // Setup structure for person item.
            $timeStamps_item = [
                'fromPerson'        => $fromPerson,
                'toPerson'       => $toPerson,
                'timeStamp'        => $timeStamp,
                'moneyAmount'       => $moneyAmount,
                'paymentMethod'     => $paymentMethod
            ];

            // Add person item to persons array.
            array_push($timeStamps, $timeStamps_item);
        }

        return $timeStamps;
    }

    public function makeTransaction($data)
    {
        try {
            //Checks if the users that the transaction will do exists in the db.
            //If yes, throw an exception
            if(!$this->checkIfExists($data)){
                throw new Exception();
            }
            else {
                try {
                
                    // Setup query
                    $sql = "UPDATE account as a
                    INNER JOIN person as p ON a.person_ID = p.person_ID
                    SET `moneyAmount` = `moneyAmount` + :moneyAmount
                    WHERE p.personName = :toName";

                    // Prepare query.
                    $statement = $this->db->prepare($sql);
                    $statement->bindValue('toName', filter_var($data->toName, FILTER_SANITIZE_STRING));
                    $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
                    $statement->execute();

                    $sql2 = "UPDATE account as a
                    INNER JOIN person as p ON a.person_ID = p.person_ID
                    SET `moneyAmount` = `moneyAmount` - :moneyAmount
                    WHERE p.personName = :fromName";

                    $statement = $this->db->prepare($sql2);
                    $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
                    $statement->bindValue('fromName', filter_var($data->fromName, FILTER_SANITIZE_STRING));
                    $statement->execute();  

                    $sql3 = "INSERT INTO `timestamp`(`fromPerson`,`moneyAmount`,`timeStamp`,`toPerson`, `paymentMethod`)
                    VALUES(:fromName, :moneyAmount, NOW(), :toName, :paymentMethod)";

                    $statement = $this->db->prepare($sql3);
                    $statement->bindValue('fromName', filter_var($data->fromName, FILTER_SANITIZE_STRING));
                    $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
                    $statement->bindValue('toName', filter_var($data->toName, FILTER_SANITIZE_STRING));
                    $statement->bindValue('paymentMethod', filter_var($data->paymentMethod, FILTER_SANITIZE_STRING));
                    return $statement->execute();
                
                } catch (Exception $e) {
                    echo 'Caught exception: the transfer failed ',  $e->getMessage(), "\n";
                }
            }
            
        } catch (Exception $e) {
            echo 'Caught exception: The users doesnt exist ',  $e->getMessage(), "\n";
        }
    }

    public function checkIfExists($data)
    {
        //Will return false if the users doesnt match.
        $sql = "SELECT * FROM person
        WHERE personName = :fromName AND personName = :toName";

        $statement = $this->db->prepare($sql);
        $statement->bindValue('fromName', filter_var($data->fromName, FILTER_SANITIZE_STRING));
        $statement->bindValue('toName', filter_var($data->toName, FILTER_SANITIZE_STRING));
        return $statement->execute();
    }

}
