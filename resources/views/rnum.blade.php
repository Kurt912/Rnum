<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="{{ asset('css/rnum.css') }}">

</head>

{{-- <body>

    <div class="shadow">
        <h1 id="random-digits-container"></h1>
    </div>
    <div class="card">
        <div class="card__overlay"></div>
        <div class="card__button"></div>

        <div class="card__counter">
            <span>20</span>
            <div id="num" class="card__counter__num">
                0
            </div>
            <span>40</span>
        </div>
    </div>
</body> --}}

<body>
    <div id="container">
        <div id="clockBox">
            <div id="clock">
                <div class="contents" id="random-digits-container">0, 0, 0, 0, 0</div>
            </div>
        </div>
        <div id="clockBox">
            <div id="clock">
                <div class="contents" id="timeElem">00:00</div>
            </div>
        </div>
        <div id="buttonBox"> </div>
        <button type="button" id="startButton">Start timer</button>
    </div>
</body>
<script>
    // Get the button element by its ID
    var startButton = document.getElementById("startButton");

    // Get the time element by its ID
    var timeElem = document.getElementById("timeElem");

    // Variable to store the timer interval


    // Event listener for the button click

    var timer;

    startButton.addEventListener("click", function() {
        clearInterval(timer);
        var startTime = Date.now();
        storedNum = [];
        timer = setInterval(function() {
            var currentTime = Date.now();
            var elapsedTime = Math.floor((currentTime - startTime) / 1000); // in seconds

            var minutes = Math.floor(elapsedTime / 60);
            var remainingSeconds = elapsedTime % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

            timeElem.textContent = minutes + ":" + remainingSeconds;

            if (elapsedTime % 60 == 0) {

                storeLastDigit();
            }
            console.log(elapsedTime);
            if (elapsedTime == 300) {
                storeNumberToDatabase()
                clearInterval(timer);
                timeElem.textContent = "00:00";
            }
        }, 1000);
    });

    //////////////////////////////////////
    var storedNum = [];
    var countdownDuration = 5;
    var countdownTimer;


    function getRandomDigits() {
        var length = 5;
        $.ajax({
            url: '/generateRandomNum/' + length,
            type: 'GET',
            dataType: 'json',
            success: function(data) {

                $('#random-digits-container').text(data.join(', '));


                setTimeout(getRandomDigits, 1000);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }



    function storeLastDigit() {

        var lastDigit = parseInt($('#random-digits-container').text().trim().split(', ').pop());


        storedNum.push(lastDigit);



    }

    function storeNumberToDatabase() {


        $.ajax({
            url: '/storeTransaction',
            method: 'POST',
            data: {
                storedNum: storedNum.join('')

            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                console.log('Transaction stored successfully:', response);

            },
            error: function(xhr, status, error) {
                console.error('Error storing transaction:', error);

            }
        });
    }


    $(document).ready(function() {
        getRandomDigits();


    });
</script>


</html>
