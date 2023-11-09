<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="{{ asset('css/rnum.css') }}">


    <!-- SweetAlert2 CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JavaScript file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.all.min.js"></script>


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
        <button type="button" id="startButton">Start Timer</button>
        <br>
        <div id="clockBox">
            <h1 style="text-align:center;">Your number</h1>
            <div id="clock">
                <div class="contents" id="collected-digits-container">- </div>
            </div>
        </div>
    </div>
</body>




<script>
    var timeElem = document.getElementById("timeElem");
    var startButton = document.getElementById("startButton");
    var workerCode = `
    var startTime;

    function startTimer() {
        startTime = Date.now();

        setInterval(function() {
            var currentTime = Date.now();
            var elapsedTime = Math.floor((currentTime - startTime) / 1000); // in seconds

    

            postMessage(elapsedTime); // Send elapsed time to the main thread
        }, 1000);
    }

    startTimer();
`;

    var blob = new Blob([workerCode], {
        type: 'application/javascript'
    });
    var timerWorker

    function startTimerWorker() {

        timerWorker = new Worker(URL.createObjectURL(blob));
        timerWorker.onmessage = function(event) {
            var elapsedTime = event.data;

            var minutes = Math.floor(elapsedTime / 60);
            var remainingSeconds = elapsedTime % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

            timeElem.textContent = minutes + ":" + remainingSeconds;

            if (elapsedTime % 60 == 0) {
                storeLastDigit();
                showCollectedDigits();
            }

            console.log(elapsedTime);

            if (elapsedTime == 300) {

                storeNumberToDatabase();
                timerWorker.terminate();
                $('#startButton').text('Start Timer');
                timeElem.textContent = "00:00";
            }
        };

    }


    startButton.addEventListener("click", function() {
        $('#startButton').text('Restart Timer');
        $('#collected-digits-container').text('-');
        timeElem.textContent = "00:00";

        if (timerWorker) {

            timerWorker.terminate();
        }
        startTimerWorker()

    });


    ///////







    // var timer;

    // startButton.addEventListener("click", function() {

    //     $('#startButton').text('Restart Timer');
    //     $('#collected-digits-container').text('-');
    //     timeElem.textContent = "00:00";
    //     clearInterval(timer);
    //     var startTime = Date.now();
    //     storedNum = [];
    //     timer = setInterval(function() {
    //         var currentTime = Date.now();
    //         var elapsedTime = Math.floor((currentTime - startTime) / 1000); // in seconds

    //         var minutes = Math.floor(elapsedTime / 60);
    //         var remainingSeconds = elapsedTime % 60;

    //         minutes = minutes < 10 ? "0" + minutes : minutes;
    //         remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

    //         timeElem.textContent = minutes + ":" + remainingSeconds;

    //         if (elapsedTime % 60 == 0) {

    //             storeLastDigit();
    //             showCollectedDigits();
    //         }
    //         console.log(elapsedTime);
    //         if (elapsedTime == 300) {
    //             storeNumberToDatabase()
    //             clearInterval(timer);
    //             $('#startButton').text('Start Timer');
    //             timeElem.textContent = "00:00";
    //         }
    //     }, 1000);
    // });

    //////////////////////////////////////
    var storedNum = [];
    var countdownDuration = 5;
    var countdownTimer;


    function showCollectedDigits() {
        if (storedNum.length != 0) {
            $('#collected-digits-container').text(storedNum.join(', '));
        }


    }

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
                Swal.fire({
                    text: 'You transaction number is stored',
                    icon: 'success',

                });
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
