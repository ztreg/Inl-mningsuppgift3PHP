<?php
include_once 'db.php';

class person
{
    private $db;

    public function __construct()
    {
        $db = new DB();
        $this->db = $db->getDB();
    }

    public function getpersons($id = null, $limit = null)
    {
        // Setup query.
        $sql = 'SELECT * FROM person';
        $parameters = null;

        if ($id !== null) {
            // If caller has provided id, then let's just look for that one person.
            $sql .= " WHERE id = :id ";
            $parameters = ['id' => $id];
        } elseif ($limit !== null) {
            // If caller want's to limit the number of persons.
            $sql .= ' LIMIT ' . $limit;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($parameters);

        // Setup array to contain persons.
        $persons = [];

        // Fetch is faster than fetchall.
        while ($row = $statement->fetch()) {
            // Extract $row['personCode'] to $personCode etc.
            extract($row);

            // Setup structure for person item.
            $person_item = [
                'id'                => $id,
                'personName'        => $personName,
                'moneyAmount'       => $moneyAmount,
            ];

            // Add person item to persons array.
            array_push($persons, $person_item);
        }

        return $persons;
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
                'paymentMethod'     => $paymentMethod,
            ];

            // Add person item to persons array.
            array_push($timeStamps, $timeStamps_item);
        }

        return $timeStamps;
    }

    public function makeTransaction($data)
    {
        try {
            if ($this->checkAmount($data) == false) {
                throw new Exception('nono.');
            } else {
                // Setup query
                $sql = "UPDATE person 
                SET `moneyAmount` = `moneyAmount` + :moneyAmount
                WHERE personName = :toName";

                // Prepare query.
                $statement = $this->db->prepare($sql);
                $statement->bindValue('toName', filter_var($data->toName, FILTER_SANITIZE_STRING));
                $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
                $statement->execute();


                $sql2 = "UPDATE person 
                SET moneyAmount = moneyAmount - :moneyAmount 
                WHERE personName = :fromName";
                //$test = "SELECT moneyAmount FROM person WHERE personName = :fromName";
                $statement = $this->db->prepare($sql2);
                $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
                $statement->bindValue('fromName', filter_var($data->fromName, FILTER_SANITIZE_STRING));
                $statement->execute();

                //$sql2 = "UPDATE Person
                //SET moneyAmount = IF($test > 0, moneyAmount - :moneyAmount, moneyAmount)
                //WHERE personName = :fromName";      

                $sql3 = "INSERT INTO `timestamp` (`fromPerson`,`moneyAmount`,`timeStamp`,`toPerson`, `paymentMethod`)
                VALUES(:fromName, :moneyAmount, NOW(), :toName, :paymentMethod)";

                $statement = $this->db->prepare($sql3);
                $statement->bindValue('fromName', filter_var($data->fromName, FILTER_SANITIZE_STRING));
                $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
                $statement->bindValue('toName', filter_var($data->toName, FILTER_SANITIZE_STRING));
                $statement->bindValue('paymentMethod', filter_var($data->paymentMethod, FILTER_SANITIZE_STRING));
                return $statement->execute();
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function checkAmount($data)
    {
        $sql = "SELECT moneyAmount 
        FROM Person WHERE personName = :fromName) - :moneyAmount) >= 0";

        $statement = $this->db->prepare($sql);
        $statement->bindValue('fromName', filter_var($data->fromName, FILTER_SANITIZE_STRING));
        $statement->bindValue('moneyAmount', filter_var($data->moneyAmount, FILTER_SANITIZE_STRING));
        return $statement->execute();
    }
}
