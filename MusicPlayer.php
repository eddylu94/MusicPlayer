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

        <p id="log">Hello!</p>

        <p id="bassDetection"></p>

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
                
                background-image: url(background1.jpg);
                background-size: 100% auto;
                height: 500px;
                width: 800px;
            }
            
            #visualizer #logo {
                position: absolute;
                
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);                         
                
                background-image: url(logo.png);
                background-size: 100% auto;
                position: absolute;
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

                function acquireData() {
                    requestAnimationFrame(acquireData);
                    analyser.getByteFrequencyData(audioData);
                    document.getElementById('log').innerHTML = audioData;

                    if (audioData[0] > BASS_THRESHOLD) {
                        bassDetected += "|";
                        logo.style.width = (LOGO_DEFAULT_WIDTH + (audioData[0] - BASS_THRESHOLD)) + "px";
                        logo.style.height = (LOGO_DEFAULT_HEIGHT + (audioData[0] - BASS_THRESHOLD)) + "px";
                    }
                    else {
                        logo.style.width = LOGO_DEFAULT_WIDTH + "px";
                        logo.style.height = LOGO_DEFAULT_HEIGHT + "px";
                    }
                    document.getElementById('bassDetection').innerHTML = bassDetected;
                }

                acquireData();
            };
        </script>

    </body>

</html>