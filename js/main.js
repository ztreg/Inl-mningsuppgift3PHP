$(document).ready(function () {
    //Make a get request as soon as the page loads
    //Add lists with values


    $.ajax({
        url: '/PHPinlämning3/Inl-mningsuppgift3PHP/api/generelizedApi.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {

            //Fill the dropdowns + the people list
            for (let i = 0; i < data.result.length; i++) {
                let name = data.result[i].personName;
                let amount = data.result[i].moneyAmount;
                let id = data.result[i].person_ID;
                let currency = data.result[i].currency;
                let accountNumber = data.result[i].accountNumber;

                $("#from").append(`<option class="card-text from" value="${name} ${amount} ${currency}">${name} has ${amount} ${currency}`);
                $("#to").append(`<option class="card-text to" value="${name} ${currency}">${name} has ${amount}`);
                $("#list").append(`<tr><td class="ID">${id}</td><td class="list-name">${name}</td><td>${accountNumber}</td><td class="list-amount">${amount}</td><td>${currency}</td</tr>`);

            }

            //Fill the timestamp table with the updated values
            for (let i = 0; i < data.result2.length; i++) {
                let from = data.result2[i].fromPerson;
                let to = data.result2[i].toPerson;
                let time = data.result2[i].timeStamp; 
                let cuttedTime = time.slice(0, 16)
                let amount = data.result2[i].moneyAmount;
                let method = data.result2[i].paymentMethod;
             
                $("#time").append(`<tr><td class='fromT'>${from}</td><td class='toT'>${to}</td><td class='amountT'>${amount}</td><td class='timeT'>${cuttedTime}</td><td class='methodT'>${method}</td></tr>`);
            }
        },
        error: function (request, status) {
            console.log(request);
            console.log(status);
        }

    });

    //Make a AJAX get request on submit, but prevent page reload
    $('#registrationForm').on('submit', function (e) {
        // Prevent form submission by the browser
        e.preventDefault();

        //Get all the values from the "values" in the select
        let fromArray = [$("#from").val().split(" ")];
        let fromName = fromArray[0][0];
        let fromAmount = fromArray[0][1];
        let fromCurrency = fromArray[0][2];

        let toArray = [$("#to").val().split(" ")];
        let toName = toArray[0][0];
        let toCurrency = toArray[0][1];
        var amountToSend = $(".amount").val();
        let paymentMethod = $('#method').val();

        //prepare object to send
        let dataToSend = {};

        //Get the right currency depenings on what the user sends
        $.ajax({
            url: `https://api.exchangeratesapi.io/latest?base=${fromCurrency}`,
            type: 'GET',
            dataType: 'json',
            success: function (translate) {
                switch (toCurrency) {
                    case "EUR":
                        amountToSend = amountToSend * translate.rates.EUR;
                        break;
                    case "USD":
                        amountToSend = amountToSend * translate.rates.USD;
                        break;
                    case "SEK":
                        amountToSend = amountToSend * translate.rates.SEK;
                        break;

                }
                 
                //Fill the object with the new rated amount.
                dataToSend = {
                    fromName: fromName,
                    toName: toName,
                    amount: amountToSend,
                    paymentMethod: paymentMethod
                }
                //Start the post request with the new values.
                post(dataToSend);

            },
            error: function (request, status) {
                console.log(request);
                console.log(status);
            }

        });

        function post(dataToSend) {
            //Parse the amounts to INTS before doing the check
            let fromAmountAsInt = parseInt(fromAmount);
            let amountToSendAsInt = parseInt(amountToSend);
            console.log(fromAmountAsInt + " ska skicka " + amountToSendAsInt);
            try {
                if (0 > 1) {
                    throw "The customer does not have enough money";
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/PHPinlämning3/Inl-mningsuppgift3PHP/api/generelizedApi.php",
                        data: dataToSend,
                        success: function (result) {

                            //once the post is sucessfull, make a GET request for the latest info in the database
                            //Update the values and the texts

                            $.ajax({
                                url: '/PHPinlämning3/Inl-mningsuppgift3PHP/api/generelizedApi.php',
                                type: 'GET',
                                dataType: 'json',
                                success: function (data) {
                                    //console.log(data);
                                    for (let i = 0; i < data.result.length; i++) {
                                        console.log(data);
                                        let name = data.result[i].personName;
                                        let amount = data.result[i].moneyAmount;
                                        let currency = data.result[i].currency;
                                        document.getElementsByClassName("from")[i].innerHTML = name + " has " + amount + " " + currency;
                                        document.getElementsByClassName("to")[i].innerHTML = name + " has " + amount;
                                        document.getElementsByClassName("list-name")[i].innerHTML = name;
                                        document.getElementsByClassName("list-amount")[i].innerHTML = amount;
                                        document.getElementsByClassName("from")[i].value = `${name} ${amount} ${currency}`;

                                    }
                                    for (let i = 0; i < data.result2.length; i++) {
                                        let from = data.result2[i].fromPerson;
                                        let to = data.result2[i].toPerson;
                                        let time = data.result2[i].timeStamp;
                                        let cuttedTime = time.slice(0, 16)
                                        let amount = data.result2[i].moneyAmount;
                                        let paymentMethod = data.result2[i].paymentMethod;
                                        document.getElementsByClassName("fromT")[i].innerHTML = from;
                                        document.getElementsByClassName("toT")[i].innerHTML = to;
                                        document.getElementsByClassName("amountT")[i].innerHTML = amount;
                                        document.getElementsByClassName("timeT")[i].innerHTML = cuttedTime;
                                        document.getElementsByClassName("methodT")[i].innerHTML = paymentMethod;
                                    }
                                }, //End sucess GET
                                error: function (request, status) {
                                    console.log(request);
                                    console.log(status);
                                }
                            });
                        }, // End sucess POST
                        error: function (request, status) {
                            console.log(request);
                            console.log(status);
                        }
                    });
                } //End IF 
            } //End TRY
             catch (err) {
                console.error(err);
            } //End Catch
         } //End function POST

    }); //End on submit


}); // End on dom loaded.