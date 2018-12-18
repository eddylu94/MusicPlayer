<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Music Player</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body>
        <div id="player">
            <div id="frame"></div>
            <div id="visualizer">
                <div id="logo"></div>
                <audio id="controls" controls autoplay>
                    <source src="song1.mp3" type="audio/mpeg">
                Your browser does not support the audio element.
                </audio>
            </div>
        </div>
        <script src="animation.js"></script>
    </body>
</html>