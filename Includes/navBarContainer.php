<div id="navBarContainer">
    <nav class="navBar">

        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo" >
            <img src="../Assets/images/icons/logo.png">

        </span>


        <div class="group">

            <div class="navItem">
                <span role="link" tabindex="0" onclick='openPage("search.php")' class="navItemLink">Search
                    <img src="../Assets/images/icons/search.png" class="icon" alt="Search">
                </span>
            </div>

        </div>

        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Browse</span>
            </div>

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
            </div>

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('setting.php')" class="navItemLink">
                    <?php echo $userLoggedIn->getFirstAndLastName(); ?></span>
            </div>
        </div>


    </nav>
</div>