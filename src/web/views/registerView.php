<?php
function echoInP($text)
{
    echo "<p class='margin0'>$text</p>";
}
?>

<head>
    <?php require 'components/head.php'; ?>
</head>

<body>
    <h1 class="title">Register Account</h1>
    <?php require "components/nav.php"; ?>
    <form class="basicForm" action="register" method="post">
        <label for="email">Adres e-mail:
            <input type="email" name="email" required />
        </label>
        <label for="username">Nazwa użytkownika:
            <input type="text" name="username" required />
        </label>
        <label for="password">Hasło:
            <input type="password" name="password" required />
        </label>
        <label for="password2">Powtórz hasło:
            <input type="password" name="password2" required />
        </label>
        <input type="submit" value="Submit" />
    </form>
    <div class="flexColumn errorList">
        <?php
        if ($model->usernameTaken) {
            echoInP($model->usernameTakenMessage);
        }

        if ($model->incorrectEmail) {
            echoInP($model->incorrectEmailMessage);
        }

        if ($model->incorrectUsername) {
            echoInP($model->incorrectUsernameMessage);
        }

        if ($model->incorrectPassword) {
            echoInP($model->incorrectPasswordMessage);
        }

        if ($model->incorrectRepeatPassword) {
            echoInP($model->incorrectRepeatPasswordMessage);
        }
        ?>
    </div>
</body>