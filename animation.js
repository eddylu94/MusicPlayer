window.onload = function () {
    const BASS_THRESHOLD = 150;
    const LOGO_DEFAULT_WIDTH = 200;
    const LOGO_DEFAULT_HEIGHT = 200;
    const NUMBER_OF_SNOWFLAKES = 100;
    const CONFIGURE_SETTING = 10;

    const audio = document.getElementById('controls');
    const logo = document.getElementById('logo');

    const context = new AudioContext();
    const source = context.createMediaElementSource(audio);
    const analyser = context.createAnalyser();
    source.connect(analyser);
    source.connect(context.destination);

    const bufferLength = analyser.frequencyBinCount;
    const audioData = new Uint8Array(bufferLength);

    const snowflakes = [NUMBER_OF_SNOWFLAKES];
    let mag_and_dir = new Array();
    let currentSnowflake = 0;

    let configureCounter = 0;

    let magnitudeMultiplier = 5.0

    function createSnowflakes() {
        for (let i = 0; i < NUMBER_OF_SNOWFLAKES; i++) {
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
    }

    function determineNumSnowflakes() {
        const rand = Math.random();
        if (rand <= .7) {
            return 1;
        }
        else if (rand > .7 && rand <= .95) {
            return 2;
        }
        return 3;
    }

    function configureSnowflakes(numSnowflakes) {
        for (let i = 0; i < numSnowflakes; i++) {
            snowflakes[i].style.top = "50%";
            snowflakes[i].style.left = "50%";
            snowflakes[i].style.transform = "translate(-50%, -50%)"

            const snowflakeWidth = 5 + 5 * Math.random();
            snowflakes[i].width = snowflakeWidth;
            snowflakes[i].height = snowflakeWidth;

            snowflakes[i].style.opacity = 0.3 + 0.7 * Math.random();
            snowflakes[i].style.borderRadius = "50%";
            snowflakes[i].style.boxShadow = "0 0 5px rgba(255,255,255,1)";

            const direction = 2 * Math.PI * Math.random();
            const magnitude = 2 * Math.random();

            mag_and_dir[currentSnowflake][0] = magnitude;
            mag_and_dir[currentSnowflake][1] = direction;
            
            currentSnowflake = currentSnowflake < NUMBER_OF_SNOWFLAKES - 1 ? currentSnowflake + 1 : 0
        }
    }

    function isWithinFrame(offsetLeft, offsetTop) { 
        return offsetLeft >= 400 - (800 / 2 + 10)
        && offsetLeft <= 400 + (800 / 2 + 10)
        && offsetTop <= 250 + (500 / 2 + 10)
        && offsetTop >= 250 - (500 / 2 + 10);
    }

    function translateSnowflakes() {
        for (let i = 0; i < NUMBER_OF_SNOWFLAKES; i++) {
            if (isWithinFrame(snowflakes[i].offsetLeft, snowflakes[i].offsetTop)) {
                const velocity_x = magnitudeMultiplier * mag_and_dir[i][0] * Math.cos(mag_and_dir[i][1]) * Math.random();
                const velocity_y = magnitudeMultiplier * mag_and_dir[i][0] * Math.sin(mag_and_dir[i][1]) * Math.random();

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
        
        const isSignificantBass = audioData[0] > BASS_THRESHOLD;
        logo.style.width = isSignificantBass ? (LOGO_DEFAULT_WIDTH + (audioData[0] - BASS_THRESHOLD)) + "px" : LOGO_DEFAULT_WIDTH + "px";             
        logo.style.height = isSignificantBass ? (LOGO_DEFAULT_HEIGHT + (audioData[0] - BASS_THRESHOLD)) + "px" : logo.style.height = LOGO_DEFAULT_HEIGHT + "px";
        magnitudeMultiplier = isSignificantBass ? 1 + 30 * (audioData[0] - BASS_THRESHOLD) / BASS_THRESHOLD : 1;
    }

    function update_snowflakes() {
        if (configureCounter == CONFIGURE_SETTING) {
            configureSnowflakes(determineNumSnowflakes());
            configureCounter = 0;
        }
        translateSnowflakes();
        configureCounter++;
    }

    createSnowflakes();
    acquireData();
    setInterval(update_snowflakes, 1);
};