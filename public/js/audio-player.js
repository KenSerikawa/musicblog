  //turn 128 seconds into 2:08
function getTimeCodeFromNum(num) {
    let seconds = parseInt(num);
    let minutes = parseInt(seconds / 60);
    seconds -= minutes * 60;
    const hours = parseInt(minutes / 60);
    minutes -= hours * 60;

    if (hours === 0) return `${minutes}:${String(seconds % 60).padStart(2, 0)}`;
    return `${String(hours).padStart(2, 0)}:${minutes}:${String(
        seconds % 60
    ).padStart(2, 0)}`;
}

function arraySearch(arr,val) {
    for (var i=0; i<arr.length; i++)
        if (arr[i] === val)                    
            return i;
    return false;
}


var audioPlayer = document.querySelectorAll(".audio-player");
var songs = document.querySelectorAll('audio');

songs.forEach(element => {
    var index = arraySearch(songs, element);
    var audio = new Audio(
        element.children[0].src
    );
    console.dir(audio);

    audio.addEventListener(
    "loadeddata",
    () => {
        audioPlayer[index].querySelector(".time .length").textContent = getTimeCodeFromNum(
        audio.duration
        );
        audio.volume = .75;
    },
    false
    );

    //click on timeline to skip around
    const timeline = audioPlayer[index].querySelector(".timeline");
    timeline.addEventListener("click", e => {
    const timelineWidth = window.getComputedStyle(timeline).width;
    const timeToSeek = e.offsetX / parseInt(timelineWidth) * audio.duration;
    audio.currentTime = timeToSeek;
    }, false);

    //click volume slider to change volume
    const volumeSlider = audioPlayer[index].querySelector(".controls .volume-slider");
    volumeSlider.addEventListener('click', e => {
    const sliderWidth = window.getComputedStyle(volumeSlider).width;
    const newVolume = e.offsetX / parseInt(sliderWidth);
    audio.volume = newVolume;
    audioPlayer[index].querySelector(".controls .volume-percentage").style.width = newVolume * 100 + '%';
    }, false)

    //check audio percentage and update time accordingly
    setInterval(() => {
    const progressBar = audioPlayer[index].querySelector(".progress");
    progressBar.style.width = audio.currentTime / audio.duration * 100 + "%";
    audioPlayer[index].querySelector(".time .current").textContent = getTimeCodeFromNum(
        audio.currentTime
    );
    }, 500);

    //toggle between playing and pausing on button click
    const playBtn = audioPlayer[index].querySelector(".controls .toggle-play");
    playBtn.addEventListener(
    "click",
    () => {
        if (audio.paused) {
        playBtn.classList.remove("play");
        playBtn.classList.add("pause");
        audio.play();
        } else {
        playBtn.classList.remove("pause");
        playBtn.classList.add("play");
        audio.pause();
        }
    },
    false
    );

    audioPlayer[index].querySelector(".volume-button").addEventListener("click", () => {
    const volumeEl = audioPlayer[index].querySelector(".volume-container .volume");
    audio.muted = !audio.muted;
    if (audio.muted) {
        volumeEl.classList.remove("icono-volumeMedium");
        volumeEl.classList.add("icono-volumeMute");
    } else {
        volumeEl.classList.add("icono-volumeMedium");
        volumeEl.classList.remove("icono-volumeMute");
    }
    });
});