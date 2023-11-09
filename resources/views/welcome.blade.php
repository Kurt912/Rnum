<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body>

    <div class="shadow">
        <h1 id="random-digits-container">ew</h1>
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
</body>
<script>
    var storedNum = [];
    var countdownDuration = 5;
    var countdownTimer;
    $('.card__button').click(function clicked() {
        // alert('This is an alert message!');
        $(this).addClass('card__button--triggered');

        $(this).off('click', clicked);

        var count = 0;
        var counter = setInterval(timer, 1000);

        function timer() {
            count += 1;
            if (count === 60) {
                clearInterval(counter);

                setTimeout(function() {
                    count = 0;
                    document.getElementById("num").innerHTML = count;

                    $('.card__button').removeClass('card__button--triggered');
                    $('.card__button').on('click', clicked);

                }, 800);

                return;
            }
            document.getElementById("num").innerHTML = count;
        }

    });

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

    function startCountdown() {

        countdownTimer = setInterval(function() {
            countdownDuration--;

            if (countdownDuration <= 0) {

                storeLastDigit();
             
                countdownDuration = 5;
            }
        }, 1000);
    }

    function storeLastDigit() {

        var lastDigit = parseInt($('#random-digits-container').text().trim().split(', ').pop());


        storedNum.push(lastDigit);
        if (storedNum.length == 5) {
            alert(storedNum)
            storedNum = [];
            clearInterval(countdownTimer)
        }
      

    }
    $(document).ready(function() {
        getRandomDigits();

        $('.card__button').click(function() {

            startCountdown();

        });
    });
</script>


</html>
