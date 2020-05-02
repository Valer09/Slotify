<?php

include ("../../config.php");



if (isset($_POST['playlistId']) && isset($_POST['songId'])){

    $songId = $_POST['songId'];
    $playlistId = $_POST['playlistId'];
    $query = mysqli_query($con, "SELECT songId FROM playlistSongs WHERE playlistId='$playlistId' AND songId='$songId' ");

    if (mysqli_num_rows($query) == 0) {

        $orderIdQuery = mysqli_query($con, "SELECT IFNULL(MAX(playlistOrder) + 1, 1)  as playlistOrder FROM playlistSongs WHERE playlistId='$playlistId'");

        $row = mysqli_fetch_array($orderIdQuery);
        $order = $row['playlistOrder'];
        $query = mysqli_query($con, "INSERT IGNORE INTO playlistSongs VALUES ('0', '$songId', '$playlistId', '$order')");
    }
    else{

        echo "This song already exists in playlist";

    }

}else{

    echo "PlaylistId or SongId was not passed into addToPlaylist.php";
}

?>
