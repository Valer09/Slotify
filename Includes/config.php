<?php

ob_start();
session_start();

$timezone = date_default_timezone_set("Europe/Rome");

$con = mysqli_connect("eu-cdbr-west-03.cleardb.net", "bf1d3cf829ee43", "8c812b6f", "heroku_94917b47a08ec11","3306");
//$con->set_charset("latin1_swedish_ci");

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}

?>