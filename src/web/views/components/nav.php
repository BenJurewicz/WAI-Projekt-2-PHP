<?php
require_once "classes/user.class.php";
?>

<nav>
    <a href="/">Gallery</a>
    <a href="/upload">Upload Image</a>
    <?php
    // if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
    if (User::isLoggedIn()) {
        echo "<a href='/logout'>Logout</a>";
    } else {
        echo "<a href='/login'>Login</a>";
        echo "<a href='/register'>Register</a>";
    }
    ?>
</nav>