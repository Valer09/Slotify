<?php

ob_start();
session_start();

$timezone = date_default_timezone_set("Europe/Rome");

$con = mysqli_connect("192.168.2.100:3307", "root", "Oliviero_91", "slotify","3307");
//$con->set_charset("latin1_swedish_ci");

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}

?>