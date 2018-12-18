window.onload = function () {

    var audio = document.getElementById('controls');

    var context = new AudioContext();
    var source = context.createMediaElementSource(audio);
    var analyser = context.createAnalyser();

    source.connect(analyser);
    source.connect(context.destination);

    var bufferLength = analyser.frequencyBinCount;
    var audioData = new Uint8Array(bufferLength);

    var BASS_THRESHOLD = 150;
    var bassDetected = "";

    var logo = document.getElementById('logo');
    var LOGO_DEFAULT_WIDTH = 200;
    var LOGO_DEFAULT_HEIGHT = 200;

    var snowflakes = [100];
    var mag_and_dir = new Array();
    var currentSnowflake = 0;

    var configureCounter = 0;
    var configureSetting = 10;

    var magnitudeMultiplier = 5.0

    for (var i = 0; i < 100; i++) {
        snowflakes[i] = new Image();
        snowflakes[i].src = "white_circle.png";

        snowflakes[i].style.position = "absolute";
        snowflakes[i].style.zIndex = "200";
        snowflakes[i].style.top = "50%";
        snowflakes[i].style.left = "50%";
        snowflakes[i].style.transform = "translate(-50%, -50%)"
        snowflakes[i].width = 10;
        snowflakes[i].height = 10;

        mag_and_dir[i] = new Array();
        mag_and_dir[i].push(0);
        mag_and_dir[i].push(0);

        document.getElementById('visualizer').appendChild(snowflakes[i]);
    }

    function determineNumSnowflakes() {
        var numSnowflakes;
        var rand = Math.random();
        if (rand <= .7) {
            numSnowflakes = 1;
        }
        else if (rand > .7 && rand <= .95) {
            numSnowflakes = 2;
        }
        else {
            numSnowflakes = 3;
        }
        return numSnowflakes;
    }

    function configureSnowflakes(numSnowflakes) {
        for (var i = 0; i < numSnowflakes; i++) {

            snowflakes[currentSnowflake].style.top = "50%";
            snowflakes[currentSnowflake].style.left = "50%";
            snowflakes[currentSnowflake].style.transform = "translate(-50%, -50%)"

            var snowflakeWidth = 5 + 5 * Math.random();
            snowflakes[currentSnowflake].width = snowflakeWidth;
            snowflakes[currentSnowflake].height = snowflakeWidth;

            snowflakes[currentSnowflake].style.opacity = 0.3 + 0.7 * Math.random();
            snowflakes[currentSnowflake].style.borderRadius = "50%";
            snowflakes[currentSnowflake].style.boxShadow = "0 0 5px rgba(255,255,255,1)";

            var direction = 2 * Math.PI * Math.random();
            var magnitude = 2 * Math.random();

            mag_and_dir[currentSnowflake][0] = magnitude;
            mag_and_dir[currentSnowflake][1] = direction;

            if (currentSnowflake < 99) {
                currentSnowflake++;
            }
            else {
                currentSnowflake = 0;
            }
        }
    }

    function translateSnowflakes() {
        for (var i = 0; i < 100; i++) {
            if (snowflakes[i].offsetLeft >= 400 - (800 / 2 + 10)
                && snowflakes[i].offsetLeft <= 400 + (800 / 2 + 10)
                && snowflakes[i].offsetTop <= 250 + (500 / 2 + 10)
                && snowflakes[i].offsetTop >= 250 - (500 / 2 + 10)) {

                var velocity_x = magnitudeMultiplier * mag_and_dir[i][0] * Math.cos(mag_and_dir[i][1]) * Math.random();
                var velocity_y = magnitudeMultiplier * mag_and_dir[i][0] * Math.sin(mag_and_dir[i][1]) * Math.random();

                snowflakes[i].style.left = (snowflakes[i].offsetLeft + velocity_x) + "px";
                snowflakes[i].style.top = (snowflakes[i].offsetTop + velocity_y) + "px";
            }
            else {
                mag_and_dir[i][0] = 0;
                mag_and_dir[i][1] = 0;
            }
        }
    }

    function acquireData() {
        requestAnimationFrame(acquireData);
        analyser.getByteFrequencyData(audioData);
        //document.getElementById('log').innerHTML = audioData;
        //console.log(audioData);

        if (audioData[0] > BASS_THRESHOLD) {
            bassDetected += "|";
            logo.style.width = (LOGO_DEFAULT_WIDTH + (audioData[0] - BASS_THRESHOLD)) + "px";
            logo.style.height = (LOGO_DEFAULT_HEIGHT + (audioData[0] - BASS_THRESHOLD)) + "px";
            magnitudeMultiplier = 1 + 30 * (audioData[0] - BASS_THRESHOLD) / BASS_THRESHOLD;
        }
        else {
            logo.style.width = LOGO_DEFAULT_WIDTH + "px";
            logo.style.height = LOGO_DEFAULT_HEIGHT + "px";
            magnitudeMultiplier = 1;
        }
        //document.getElementById('bassDetection').innerHTML = bassDetected;
    }

    function update_snowflakes() {
        if (configureCounter == configureSetting) {
            configureSnowflakes(determineNumSnowflakes());
            configureCounter = 0;
        }
        translateSnowflakes();
        configureCounter++;
    }

    acquireData();
    setInterval(update_snowflakes, 1);
};