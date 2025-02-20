<?php
include("../Includes/config.php");
include("../Includes/classes/User.php");
include("../Includes/classes/Artist.php");
include("../Includes/classes/Album.php");
include("../Includes/classes/Song.php");
include("../Includes/classes/Playlist.php");

//session_destroy(); LOGOUT

if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
    $username = $userLoggedIn->getUsername();
	echo "<script> userLoggedIn = '$username'</script>";
}
else {
	header("Location: register.php");
}

?>

<html>
<head>
	<title>Welcome to Slotify!</title>
	<link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
    <script src="../Includes/Jquery.js"></script>
    <script src="../Assets/js/script.js"></script>
</head>

<body>

	<div id="mainContainer">

		<div id="topContainer">

			<?php include("../Includes/navBarContainer.php"); ?>

			<div id="mainViewContainer">

				<div id="mainContent">