<?php
include_once 'MySQLDB.php';
class personClass
{
    private $db;

    public function __construct(MySQLDB $db)
    {
        $this->db = $db->getConnection();
    }

    public function getPersons()
    {
        try {
            $sql = 'SELECT * FROM person as p
            INNER JOIN account as a ON p.accountNumber = a.accountNumber';
            $parameters = null;

            $statement = $this->db->prepare($sql);
            $statement->execute($parameters);

            $persons = [];

            while ($row = $statement->fetch()) {

                extract($row);

                $person_item = [
                    'person_ID'         => $person_ID,
                    'personName'        => $personName,
                    'account_ID'        => $account_ID,
                    'accountNumber'     => $accountNumber,
                    'moneyAmount'       => $moneyAmount,
                    'currency'          => $currency,
                ];

                array_push($persons, $person_item);
            }

            return $persons;
        } catch (\Exception $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
            echo "Failed to get all persons: " . $e->getMessage();
        }
    }
}
