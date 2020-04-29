<?php
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();

while($row = mysqli_fetch_array($songQuery)){
    array_push($resultArray, $row['id']);
}

$jsonArray = json_encode($resultArray);

?>


<script>
    $(document).ready(function(){
        var newPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false);
        updateVolumeProgressBar(audioElement.audio);

        //.on(params) is a short way to .mousedown() .mousemove() ...
        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function (e) {
            e.preventDefault();
        });

        $(".playbackBar .progressBar").mousedown(function(){
            mouseDown = true;
        });

        $(".playbackBar .progressBar").mousemove(function(e){
            if (mouseDown)
                timeFromOffset(e, this);
            });

        $(".playbackBar .progressBar").mouseup(function(e){
            timeFromOffset(e, this);
        });


        $(".volumeBar .progressBar").mousedown(function(){
            mouseDown = true;
        });

        $(".volumeBar .progressBar").mousemove(function(e){
            if (mouseDown){
                var percentage = e.offsetX / $(this).width();

                if (percentage >= 0 && percentage <= 1){
                    var percentage = e.offsetX / $(this).width();
                    audioElement.audio.volume = percentage;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function(e){
            var percentage = e.offsetX / $(this).width();

            if (percentage >= 0 && percentage <= 1){
                var percentage = e.offsetX / $(this).width();
                audioElement.audio.volume = percentage;
            }
        });

        $(document).mouseup(function(e){
            mouseDown = false;
        });
    });

    function timeFromOffset(mouse, progressBar){
        var percentage = mouse.offsetX / $(progressBar).width() * 100;
        var seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds);
    }

    function setMute(){
        audioElement.audio.muted = !audioElement.audio.muted;

        var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
        $(".controlButton.volume img").attr("src", "../Assets/images/icons/" + imageName);
    }

    function setRepeat(){
        repeat = !repeat;
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".controlButton.repeat img").attr("src", "../Assets/images/icons/" + imageName);
    }

    function setShuffle(){
        isShuffle = !isShuffle;
        var imageName = isShuffle ? "shuffle-active.png" : "shuffle.png";
        $(".controlButton.shuffle img").attr("src", "../Assets/images/icons/" + imageName);

        if(isShuffle){
            shuffle(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
        else{
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }


    }

    /**
     * Shuffles array in place.
     * @param {Array} a items An array containing the items.
     */
    function shuffle(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    }


    /**
     * border: 2px solid rgba(7, 209, 89, .4);
     * border-radius: 250px
     *
     * **/


    function nextSong() {
        console.log(currentIndex);
        if (repeat) {
            audioElement.setTime(0);
            playSong();
            return;
        }

        if(currentIndex === currentPlaylist.length -1)
            console.log("index max")

        else{

            currentIndex ++;
            var trackToPlay = isShuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
            setTrack(trackToPlay, currentPlaylist, isPlaying);
            isPlaying ? playSong() : pauseSong();
        }




    }

    function previousSong() {

        if(audioElement.audio.currentTime >= 3 || currentIndex == 0){
            audioElement.setTime(0);
            playSong();

        }else{
            currentIndex --;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, isPlaying);
            isPlaying ? playSong() : pauseSong();
        }
    }

    function setTrack(trackId, newPlaylist, play) {

        if (newPlaylist !== currentPlaylist){
            currentPlaylist = newPlaylist;
            shufflePlaylist = currentPlaylist.slice();
            shuffle(shufflePlaylist);
        }

        if (isShuffle)
            currentIndex = shufflePlaylist.indexOf(trackId);
        else
            currentIndex = currentPlaylist.indexOf(trackId);

        $.post("../Includes/handlers/ajax/getSongJson.php", {songId: trackId}, function(data){

            currentIndex = currentPlaylist.indexOf(trackId);

            var track = JSON.parse(data);
            $(".trackName span").text(track.title);

            $.post("../Includes/handlers/ajax/getArtistJson.php", {artistId: track.artist} , function(data){
                var artist = JSON.parse(data);
                $(".trackInfo .artistName span").text(artist.name);
                $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" +artist.id +" ')");
            });

            $.post("../Includes/handlers/ajax/getAlbumJson.php", {albumId: track.album} , function(data){
                var album = JSON.parse(data);
                $(".content .albumLink img").attr("src", "../"+album.artworkPath);
                $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" +album.id +" ')");
                $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" +album.id +" ')");
            });

            audioElement.setTrack(track);

            if (play)
                playSong();
            else
                pauseSong();

        });


    }

    function playSong(){

        if (audioElement.audio.currentTime === 0)
            $.post("../Includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});

        isPlaying=true;
        audioElement.play();
        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
    }
    function pauseSong() {
        isPlaying=false;
        audioElement.pause();
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
    }

</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img role="link" tabindex="0" src="" class="albumArtwork">
                </span>
                <div class="trackInfo">
                    <span class="trackName">
                        <span role="link" tabindex="0" ></span>
                    </span>
                    <span class="artistName">
                        <span role="link" tabindex="0"></span>
                    </span>
                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="../Assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>
                    <button class="controlButton previous" title="Previous button" onclick="previousSong()">
                        <img src="../Assets/images/icons/previous.png" alt="Previous">
                    </button>
                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="../Assets/images/icons/play.png" alt="Play">
                    </button>
                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="../Assets/images/icons/pause.png" alt="Pause">
                    </button>
                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="../Assets/images/icons/next.png" alt="Next">
                    </button>
                    <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
                        <img src="../Assets/images/icons/repeat.png" alt="Repeat">
                    </button>
                </div>
                <div class="playbackBar">
                    <span class="progressTime current">0.00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progressTime remaining">0.00</span>
                </div>
            </div>
        </div>
        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume button" onclick="setMute()">
                    <img src="../Assets/images/icons/volume.png" alt="Volume">
                </button>
                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>