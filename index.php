<?php

include_once('./includes/header.php');


?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Money Transactions</a>
    </nav>
    
    <form id="registrationForm" class="card-group" method="post" action="?">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">From</h5>

                <select id="from" class="card" name="name">
    
                    <option class="card-text from" value="">Send from here</option>

                </select>
                <p> Amount</p>
                <input type="number" name="amount" class="amount">
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">To</h5>
                <select id="to" class="card" name="name">
    
                    <option class="card-text to" value=""> To the people here</option>

                </select>
            </div>
        </div>

       <input type="submit" class="btn-btn primary">
    </form>
    
    <div class="row">
    <div class="card col-sm-4" >
            <div class="card-body">
                <h5 class="card-title">Live-list</h5>
                <table id="list" class="table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card col-sm-4">
            <div class="card-body">
                <h5 class="card-title">Latest 10 transactions</h5>
                <table id="time" class="table">
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Timestamp</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

include('./includes/footer.php');
