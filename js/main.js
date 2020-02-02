$(document).ready(function () {
    //Make a get request as soon as the page loads
    //Add lists with values
    $.ajax({
        url: 'http://localhost/PHPinl%C3%A4mning3/Inl-mningsuppgift3PHP/api/get.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);

            for(let i = 0; i < data.result.length; i++) {
                let name = data.result[i].personName;
                let amount = data.result[i].moneyAmount;
                let id = data.result[i].id;
               
                $("#from").append(`<option class="card-text from" value="${name}">${name} has ${amount}`);
                $("#to").append(`<option class="card-text to" value="${name}">${name} has ${amount}`);
                $("#list").append(`<tr><td class="ID">${id}</td><td class="list-name">${name}</td><td class="list-amount">${amount} kr</td></tr>`);
                $("#from").val(data.result[0].personName); 
                $("#to").val(data.result[1].personName);
            }
            console.log(data.result2);
            for(let i = 0; i < data.result2.length; i++) {
                let from = data.result2[i].fromPerson;
                let to = data.result2[i].toPerson;
                let time = data.result2[i].timeStamp;
                let amount = data.result2[i].moneyAmount;

                $("#time").append(`<tr><td class='fromT'>${from}</td><td class='toT'>${to}</td><td class='amountT'>${amount} kr</td><td class='timeT'>${time}</td></tr>`);
                console.log(i);
            }
        },
        error: function(request, status){
            console.log(request);
            console.log(status);
        }
        
    });
    
    //Make a AJAX get request on submit, but prevent page reload
    $('#registrationForm').on('submit', function (e) {
        // Prevent form submission by the browser
        e.preventDefault();

        let fromName = $("#from").val();
        let toName = $("#to").val();
        let amount = $(".amount").val();

        let dataToSend = {
            fromName: fromName,
            toName: toName,
            amount: amount
        }
        $.ajax({
            type: "POST",
            url: "http://localhost/PHPinl%C3%A4mning3/Inl-mningsuppgift3PHP/api/update.php",
            data: dataToSend,
            cache: false,
            success: function (result) {
                //check if what response is
                console.log(result);
            },
            error: function(request, status){
                console.log(request);
                console.log(status);
            }
        });
        
        // make a get request after the post to get the latest values

        $.ajax({
            url: 'http://localhost/PHPinl%C3%A4mning3/Inl-mningsuppgift3PHP/api/get.php',
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function (data) 
            {
                for(let i = 0; i < data.result.length; i++) 
                {  
                    let name = data.result[i].personName;
                    let amount = data.result[i].moneyAmount;
                    document.getElementsByClassName("from")[i+1].innerHTML = name + " has " + amount;
                    document.getElementsByClassName("to")[i+1].innerHTML = name + " has " + amount;
                    document.getElementsByClassName("list-name")[i].innerHTML = name;
                    document.getElementsByClassName("list-amount")[i].innerHTML = amount + " kr";
                }
                for(let i = 0; i < data.result.length; i++) {
                    let from = data.result2[i].fromPerson;
                    let to = data.result2[i].toPerson;
                    let time = data.result2[i].timeStamp;
                    let amount = data.result2[i].moneyAmount;
    
                    document.getElementsByClassName("fromT")[i].innerHTML = from ;
                    document.getElementsByClassName("toT")[i].innerHTML = to;
                    document.getElementsByClassName("amountT")[i].innerHTML = amount + " kr";
                    document.getElementsByClassName("timeT")[i].innerHTML = time;
                }
            },
            error: function(request, status)
            {
                console.log(request);
                console.log(status);
            }
        });
    });
});