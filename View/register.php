<?php
include("../Includes/config.php");
include("../Includes/classes/Constants.php");
include("../Includes/classes/Account.php");
$account = new Account($con);
include("../Includes/handlers/register-handler.php");
include("../Includes/handlers/login-handler.php");

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>
<html>
<head>
    <title>
        Welcome to slotify!
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../Assets/js/register.js"></script>
    <link rel="stylesheet" type="text/css" href="../Assets/css/register.css">
</head>
<body>
<?php
if (isset($_POST['registerButton'])) {
    echo '<script>
            $(document).ready(function(){
                    $("#loginForm").hide();
                    $("#registerForm").show();
            });
        </script>';
} else {
    echo '
        <script>
            $(document).ready(function(){
                    $("#loginForm").show();
                    $("#registerForm").hide();
            });
        </script>';
}
?>
<div id="background">
    <div id="loginContainer">
        <div id="inputContainer">
            <form id="loginForm" action="register.php" method="POST">
                <h2> Login to your account</h2>
                <p>
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <label for="loginUsername"> Username</label>
                    <input id="loginUsername" name="loginUsername" type="textbox" placeholder="e.g bart"
                           value="<?php getInputValue('loginUsername') ?>" required>
                </p>
                <p>
                    <label for="loginPassword">Password</label>
                    <input id="loginPassword" name="loginPassword" type="password" required>
                </p>
                <button type="submit" name="loginButton">LOG IN</button>

                <div class="hasAccountText">
                    <span id="hideLogin">Don't have an account yet? Signup here.</span>
                </div>

            </form>

            <form id="registerForm" action="register.php" method="POST">
                <h2> Create your free account </h2>
                <p>
                    <?php echo $account->getError(Constants::$userCharacters); ?>
                    <?php echo $account->getError(Constants::$usernameTaken); ?>
                    <label for="username"> Username </label>
                    <input id="username" name="username" type="textbox" placeholder="e.g bart"
                           value="<?php getInputValue('username') ?>" required>
                </p>

                <p>
                    <?php echo $account->getError(Constants::$firstnameCharacters); ?>
                    <label for="firstName"> First Name</label>
                    <input id="firstName" name="firstName" type="textbox" placeholder="e.g bart"
                           value="<?php getInputValue('firstName') ?>" required>
                </p>

                <p>
                    <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                    <label for="lastName"> Lastname </label>
                    <input id="lastName" name="lastName" type="textbox" placeholder="Simpson"
                           value="<?php getInputValue('lastName') ?>" required>
                </p>

                <p>
                    <?php echo $account->getError(Constants::$emailInvalid); ?>
                    <?php echo $account->getError(Constants::$emailTaken); ?>
                    <label for="email"> Email </label>
                    <input id="email" name="email" type="email" placeholder="example@dom.com"
                           value="<?php getInputValue('email') ?>" required>
                </p>

                <p>
                    <?php echo $account->getError(Constants::$emailsDoNoMatch); ?>
                    <label for="confirmEmail"> Confirm Email </label>
                    <input id="confirmEmail" name="confirmEmail" type="email" placeholder="example@dom.com"
                           value="<?php getInputValue('confirmEmail') ?>" required>
                </p>

                <p>
                    <?php echo $account->getError(Constants::$passwordCharacters); ?>
                    <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="yourpassword" required>
                </p>

                <p>
                    <?php echo $account->getError(Constants::$passwordDoNoMatch); ?>
                    <label for="confirmPassword">Confirm Password</label>
                    <input id="confirmPassword" name="confirmPassword" type="password" placeholder="yourpassword"
                           required>
                </p>
                <button type="submit" name="registerButton">SIGN UP</button>

                <div class="hasAccountText">
                    <span id="hideRegister">Already have an account? Log in here.</span>
                </div>

            </form>
        </div>
        <div id="loginText">
            <h1>Get great music, right now</h1>
            <h2>Listen to loads of songs for free</h2>
            <ul>
                <li>Discover Music you'fall in love with</li>
                <li>Create your own playlists</li>
                <li>Follow artists to keep up to date</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>