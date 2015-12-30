<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8" />
        <title>Music Player</title>
    </head>
    
    <body>

        <div id="visualizer">
            <div id="logo"></div>
        </div>

        <audio id="controls" controls>
            <source src="song1.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
        </audio>

        <p id="log">Hello!</p>

        <p id="bassDetection"></p>

        <style>
            body {
                background-color: black;
            }
            
            #visualizer {
                background-image: url(background1.jpg);
                background-size: 100% auto;
                height: 500px;
                width: 800px;
            }
            
            #visualizer #logo {
                left: 300px;
                top: 150px;                
                
                background-image: url(logo.png);
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

                var bassThreshold = 150;
                var bassDetected = "";

                function acquireData() {
                    requestAnimationFrame(acquireData);
                    analyser.getByteFrequencyData(audioData);
                    document.getElementById('log').innerHTML = audioData;

                    if (audioData[0] > bassThreshold) {
                        bassDetected += "|";
                    }
                    document.getElementById('bassDetection').innerHTML = bassDetected;
                }

                acquireData();
            };
        </script>

    </body>

</html>