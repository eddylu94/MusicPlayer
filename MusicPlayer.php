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
                var mag_and_dir = [100][2];
                var currentSnowflake = 0;

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

                    document.getElementById('visualizer').appendChild(snowflakes[i]);
                }

                function createSnowflakes(numSnowflakes) {
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

                        translate = setInterval(function translateSnowflake() {
                            var velocity_x = Math.cos(direction);
                            var velocity_y = Math.sin(direction);
                            
                            snowflakes[currentSnowflake].style.left = (snowflakes[currentSnowflake].offsetLeft + velocity_x) + "px";
                            snowflakes[currentSnowflake].style.top = (snowflakes[currentSnowflake].offsetTop + velocity_y) + "px";

                            if (snowflakes[currentSnowflake].offsetTop == 0) {
                                clearTimeout(translate);
                            }
                        }, 100);

                        if (currentSnowflake < 99) {
                            currentSnowflake++;
                        }
                        else {
                            currentSnowflake = 0;
                        }
                    }
                }

                function createSnowflakes_interval() {
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
                    createSnowflakes(numSnowflakes);

                    var interval = 300 + 500 * Math.random();
                    setTimeout(createSnowflakes_interval, interval);
                }

                createSnowflakes_interval();

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

                acquireData();
            };
        </script>

    </body>

</html>