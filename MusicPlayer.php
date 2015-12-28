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

        <style>
            #visualizer {
                background-color: #1A1A1A;
                height: 450px;
                width: 800px;
            }
            
            #visualizer #logo {
                left: 275px;
                top: 100px;                
                
                background-image: url(favicon.png);
                position: absolute;
                width: 250px;
                height: 250px;
                
                background-repeat: no-repeat;
                background-position: 50%;
                border-radius: 50%;
            }
            
            #controls {
                width: 800px;
            }            
        </style>

    </body>

</html>