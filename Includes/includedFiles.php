<?php

$userLoggedIn=null;

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include("../Includes/config.php");
    include("../Includes/classes/User.php");
    include("../Includes/classes/Artist.php");
    include("../Includes/classes/Album.php");
    include("../Includes/classes/Song.php");
    include("../Includes/classes/Playlist.php");


    if (isset($_GET['userLoggedIn']))
        $userLoggedIn = new User($con, $_GET['userLoggedIn']);
    else{
        echo "Username variable was not passed into the page. Check the openPage JS function";
        exit;
    }


}
else {
    include("../Includes/header.php");
    include("../Includes/footer.php");

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}

?>