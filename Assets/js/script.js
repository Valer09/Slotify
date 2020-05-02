var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var isPlaying;
var repeat = false;
var isShuffle = false;
var timer;


$(document).click(function (click) {
    var target = $(click.target);

    if (!target.hasClass("item") && !target.hasClass("optionsButton")){
        hideOptionsMenu();
    }

});

$(window).scroll(function () {
    hideOptionsMenu();
});

$(document).on("change", "select.playlist", function(){
    var select = $(this);
    var playlistId = select.val();
    var songId = select.prev(".songId").val();

    $.post("../Includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId})
        .done(function(error){

            if (error != ""){
                alert(error);
                return;
            }

            hideOptionsMenu();
            select.val("");
        });
});

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    var oldPassword = $("." + oldPasswordClass).val();
    var newPassword1 = $("." + newPasswordClass1).val();
    var newPassword2 = $("." + newPasswordClass2).val();

    $.post("../Includes/handlers/ajax/updatePassword.php",
        {
            oldPassword: oldPassword,
            newPassword1: newPassword1,
            newPassword2: newPassword2,
            username: userLoggedIn
        })
        .done(function(response){
            $("." + oldPasswordClass).nextAll(".message").text(response);
    });
}

function updateEmail(emailClass) {
    var emailValue = $("." + emailClass).val();

    $.post("../Includes/handlers/ajax/updateEmail.php", {email: emailValue, username: userLoggedIn})
        .done(function(response){
            $("." + emailClass).nextAll(".message").text(response);
        });
}

function logout() {

    $.post("../Includes/handlers/ajax/logout.php", function(){
       location.reload();
    });

}

function openPage(url){
    if (timer != null)
        clearTimeout(timer);

    if (url.indexOf("?") == -1)
        url=url+'?';

    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId) {
    var songId = $(button).prevAll(".songId").val();
    $.post("../Includes/handlers/ajax/removeFromPlaylist.php",{playlistId: playlistId, songId: songId})
        .done(function (error) {
            //DO SOMETHING
            if (error != ""){
                alert(error);
                openPage("../View/playlist.php?id=" + playlistId);
                return;
            }
            openPage("../View/playlist.php?id=" + playlistId);
        });
}

function createPlaylist(){
    console.log(userLoggedIn);
    var popup = prompt("Please enter the name of your playlist");

    if(popup != null){
        $.post("../Includes/handlers/ajax/createPlaylist.php",{name: popup, username: userLoggedIn})
            .done(function (error) {
                //DO SOMETHING
                if (error != ""){
                    alert(error);
                    return;
                }
                openPage("../View/yourMusic.php");
            });
    }
}

function deletePlaylist(playlistId){
    var prompt = confirm("Are you sure you want delete this playlist?");

    if (prompt){

        $.post("../Includes/handlers/ajax/deletePlaylist.php",{playlistId: playlistId})
            .done(function (error) {
                //DO SOMETHING
                if (error != ""){
                    alert(error);
                    return;
                }
                openPage("../View/yourMusic.php");
            });

    }
}

function hideOptionsMenu() {
    var menu = $(".optionsMenu");
    if (menu.css("display") != "none"){
        menu.css("display", "none")
    }

}

function showOptionsMenu(button) {
    var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();

    menu.find(".songId").val(songId);

    var scrollTop = $(window).scrollTop(); // Distance from top of windows to top of document
    var elementOffset = $(button).offset().top; // Distance from top of document
    var top = elementOffset - scrollTop;
    var left = $(button).position().left;

    menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
}

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

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
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
