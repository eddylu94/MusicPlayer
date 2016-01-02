<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8" />
        <title>Music Player</title>
    </head>
    
    <body>

        <div id="player">
            <div id="visualizer">
                <div id="logo"></div>
            </div>

            <audio id="controls" controls>
                <source src="song1.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
            </audio>
        </div>

        <!--
        <p id="log">Hello!</p>
        -->
        <!--
        <p id="bassDetection"></p>
        -->

        <style>
            body {
                background-color: black;
            }
            
            #player {
                margin: 0 auto;
                
                height: 520px;
                width: 820px;
            }
            
            #visualizer {
                position: relative;
                z-index: 100;
                
                background-image: url(background1.jpg);
                background-size: 100% auto;
                height: 500px;
                width: 800px;
            }
            
            #visualizer #logo {
                position: relative;
                z-index: 200;
                
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);                         
                
                background-image: url(logo.png);
                background-size: 100% auto;
                width: 200px;
                height: 200px;
                border:8px solid #FFFFFF;
                
                background-repeat: no-repeat;
                background-position: 50%;
                border-radius: 50%;

                box-shadow: 0 0 35px rgba(255,255,255,1);              
            }
            
            #controls {
                width: 800px;
            }
            
            #log, #bassDetection {
                color: #FFFFFF;
            }
        </style>

        <script>
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

                for (var i = 0; i < 100; i++) {
                    snowflakes[i] = new Image();
                    snowflakes[i].src = "white_circle.png";

                    snowflakes[i].style.position = "absolute";
                    snowflakes[i].style.zIndex = "300";
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

                        snowflakes[currentSnowflake].style.position = "absolute";
                        snowflakes[currentSnowflake].style.zIndex = "300";
                        snowflakes[currentSnowflake].style.top = "50%";
                        snowflakes[currentSnowflake].style.left = "50%";
                        snowflakes[currentSnowflake].style.transform = "translate(-50%, -50%)"

                        var snowflakeWidth = 5 + 10 * Math.random();
                        snowflakes[currentSnowflake].width = snowflakeWidth;
                        snowflakes[currentSnowflake].height = snowflakeWidth;

                        snowflakes[currentSnowflake].style.opacity = 0.3 + 0.7 * Math.random();
                        snowflakes[currentSnowflake].style.borderRadius = "50%";
                        snowflakes[currentSnowflake].style.boxShadow = "0 0 5px rgba(255,255,255,1)";

                        var direction = 2 * Math.PI * Math.random();
                        var magnitude = 5 * Math.random();

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
                        var velocity_x = mag_and_dir[i][0] * Math.cos(mag_and_dir[i][1]);
                        var velocity_y = mag_and_dir[i][0] * Math.sin(mag_and_dir[i][1]);

                        snowflakes[i].style.left = (snowflakes[i].offsetLeft + velocity_x) + "px";
                        snowflakes[i].style.top = (snowflakes[i].offsetTop + velocity_y) + "px";
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
                    }
                    else {
                        logo.style.width = LOGO_DEFAULT_WIDTH + "px";
                        logo.style.height = LOGO_DEFAULT_HEIGHT + "px";
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
        </script>

    </body>

</html>