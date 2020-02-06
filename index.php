<?php

include_once('./includes/header.php');


?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Money Transactions du är inloggad som "admin"</a>
    </nav>
    <div class="row">
        <form id="registrationForm" class="card-group" method="post" action="?">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">From</h5>

                    <select id="from" class="card" name="name">

                    </select>

                    <p>Amount <input type="number" name="amount" class="amount"></p>
                    <p>Method <select id="method" name="paymentMethod"></p>

                    <option class="card-text method" value="Card/Visa">Card/Visa</option>
                    <option class="card-text method" value="Swish">Swish</option>
                    <option class="card-text method" value="Ingen aning">Ingen aning</option>
                    <option class="card-text method" value="Cash">Cash</option>

                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">To</h5>
                    <select id="to" class="card" name="name">

                    </select>
                    <input type="submit" class="btn primary" value="Send money">

                </div>
            </div>
        </form>
        <div>
            <p>Request info: -</p>
            <p id="info"></p>
        </div>
    </div>
    <h5 class="text-center">Live-updates</h5>
    <div class="row">
        <div class="card ">
            <div class="card-body">
                <h5 class="card-title">All people</h5>
                <table id="list" class="table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Accountnumber</th>
                        <th>BankAmount</th>
                        <th>Currency</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card col-sm-6">
            <div class="card-body">
                <h5 class="card-title">Latest 10 transactions</h5>
                <table id="time" class="table">
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Timestamp</th>
                        <th>PaymentMethod</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

include('./includes/footer.php');
