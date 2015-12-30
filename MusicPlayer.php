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
                
                background-repeat: no-repeat;
                background-position: 50%;
                border-radius: 50%;
                
                box-shadow: 0 0 35px rgba(0,0,0,1);              
            }
            
            #controls {
                width: 800px;
            }
            
            #log {
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

                function acquireData() {
                    requestAnimationFrame(acquireData);
                    analyser.getByteFrequencyData(audioData);
                    document.getElementById('log').innerHTML = audioData;
                }

                acquireData();               
            };
        </script>

    </body>

</html>