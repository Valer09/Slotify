var currentPlaylist = [];
var shufflePlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var isPlaying;
var repeat = false;
var isShuffle = false;


function formatTime(seconds){

    var time= Math.round(seconds);
    var minutes = Math.floor(time /60);
    var seconds= time - (minutes * 60);

    var extraZero = (seconds < 10) ? "0" : "";

    return minutes + ":" + extraZero + seconds;


}

function updateTimeProgressBar(audio){
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(-10+audio.duration - audio.currentTime));

    var progress = audio.currentTime / audio.duration *100;

    $(".playbackBar .progress").css("width" , progress + "%");
}

function updateVolumeProgressBar(audio){
    var volume = audio.volume *100;
    $(".volumeBar .progress").css("width" , volume + "%");
}


function Audio(){
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.audio.addEventListener("ended", function () {
        nextSong();
    });

    this.audio.addEventListener("canplay", function(){

        //this refers to obj that event was called on
        var duration = formatTime(this.duration);
        $(".progressTime.current").text("0:00");
        $(".progressTime.remaining").text(duration);

    });

    this.audio.addEventListener("timeupdate", function () {
        if(this.duration){
            updateTimeProgressBar(this);
        }
    });

    this.audio.addEventListener("volumechange", function(){
        updateVolumeProgressBar(this);
    });

    this.setTrack = function(track){
        this.currentlyPlaying = track;
        this.audio.src = "../"+track.path;
    }

    this.play = function () {
        isPlaying=true;
        this.audio.play();

    }

    this.pause = function () {
        isPlaying=false;
        this.audio.pause();
    }

    this.setTime = function (seconds) {
        this.audio.currentTime = seconds;

    }

}
