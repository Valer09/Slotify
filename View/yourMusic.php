<?php

include("../Includes/includedFiles.php");

?>

<div class="playlistContainer">

    <div class="gridViewContainer">

        <h2>PLAYLIST</h2>

        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">
                NEW PLAYLIST
            </button>
        </div>
    </div>

    <?php

    $username =$userLoggedIn->getUsername();

    $playlistQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$username'  ");

    if(mysqli_num_rows($playlistQuery) == 0) {
        echo "<span class='noResults'>No playlists found </span>";
    }

    while($row = mysqli_fetch_array($playlistQuery)) {

        $playlist = new Playlist($con, $row);

        echo "<div class='gridViewItem' role='link' tabindex='0' 
                    onclick='openPage(\"playlist.php?id=".$playlist->getId()."\")'>
                
                <div class='playlistImage'>
                    <img src='../Assets/images/icons/playlist.png'>
                
                </div>
				
				<div class='gridViewInfo'>"
                    . $playlist->getName() .
                "</div>
            </div>";

    }


    ?>




</div>
