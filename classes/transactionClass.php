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

    public function makeTransaction($data)
    {
        // Setup query.
        $sql = "UPDATE person SET moneyAmount = :amount WHERE personName = :name";

        // Prepare query.
        $statement = $this->db->prepare($sql);

        // Bind values.
        $statement->bindValue('name', filter_var($data->name, FILTER_SANITIZE_STRING));
        $statement->bindValue('amount', filter_var($data->amount, FILTER_SANITIZE_STRING));


        // Execute query and return result.
        return $statement->execute();
    }

}
