<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include("../Includes/config.php");
    include("../Includes/classes/Artist.php");
    include("../Includes/classes/Album.php");
    include("../Includes/classes/Song.php");

}
else {
    include("../Includes/header.php");
    include("../Includes/footer.php");

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}

?>