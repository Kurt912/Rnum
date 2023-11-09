// timer-worker.js
var startTime;

function startTimer() {
    startTime = Date.now();

    setInterval(function() {
        var currentTime = Date.now();
        var elapsedTime = Math.floor((currentTime - startTime) / 1000); // in seconds

        // ... your existing timer logic here ...

        postMessage(elapsedTime); // Send elapsed time to the main thread
    }, 1000);
}

startTimer();
