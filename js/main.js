$(document).ready(function () {
    //Make a get request as soon as the page loads
    //Add lists with values


    $.ajax({
        url: '/PHPinlämning3/Inl-mningsuppgift3PHP/api/generelizedApi.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function (data) {

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

            for (let i = 0; i < data.result2.length; i++) {
                let from = data.result2[i].fromPerson;
                let to = data.result2[i].toPerson;
                let time = data.result2[i].timeStamp;
                let currency = data.result[i].currency;
                let cuttedTime = time.slice(0, 16)
                let amount = data.result2[i].moneyAmount;
                let method = data.result2[i].paymentMethod;
                //console.log(from + " " + to);

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
            cache: false,
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
                console.log(amountToSend);
                dataToSend = {
                    fromName: fromName,
                    toName: toName,
                    amount: amountToSend,
                    paymentMethod: paymentMethod
                }
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
                if (amountToSendAsInt > fromAmountAsInt) {
                    throw "The customer does not have enough money";
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/PHPinlämning3/Inl-mningsuppgift3PHP/api/generelizedApi.php",
                        data: dataToSend,
                        cache: false,
                        success: function (result) {

                            //once the post is sucessfull, make a GET request for the latest info in the database
                            //Update the values and the texts

                            $.ajax({
                                url: '/PHPinlämning3/Inl-mningsuppgift3PHP/api/generelizedApi.php',
                                type: 'GET',
                                dataType: 'json',
                                cache: false,
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
                                },
                                error: function (request, status) {
                                    console.log(request);
                                    console.log(status);
                                }
                            });
                        },
                        error: function (request, status) {
                            console.log(request);
                            console.log(status);
                        }
                    });
                }
            } catch (err) {
                console.error(err);
            }
        }

    });


});