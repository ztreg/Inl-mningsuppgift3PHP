<?php
include('./includes/header.php');
// Person class
include_once('./classes/transactionClass.php');
$persons_object = new Person();

// Check if querystring is set to look for id or number of persons.
$no_of_persons = $_GET['no'] ?? null;
$person_id = $_GET['id'] ?? null;

// Get persons.
$persons = $persons_object->getPersons($person_id, $no_of_persons);


?>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Money Transactions</a>
    </nav>
    <div>
    <form class="card-group" method="post" action="./api/post.php">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">From</h5>
                <select class="card sm-2" name="name">
            <?php foreach ($persons as $person) : ?>
                <option class=" card-text" value=" <?php echo $person['personName']; ?>"> <?php echo $person['personName']; ?> has currently <?php echo $person['moneyAmount']; ?></option>
                <?php endforeach; ?>
                </select>
                <input type="number" name="amount">
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">To</h5>
                <select class="card sm-2"">
                <?php foreach ($persons as $person) : ?>
                    <option class=" card-text" value=" <?php echo $person['personName']; ?>"> <?php echo $person['personName']; ?> has <?php echo $person['moneyAmount']; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>

       <input type="submit" class="btn-btn primary">
    </form>
</div>

</div>

<?php

include('./includes/footer.php');
