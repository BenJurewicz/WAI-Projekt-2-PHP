<head>
    <?php require 'components/head.php'; ?>
</head>

<body>
    <h1 class="title">Login</h1>
    <?php require "components/nav.php"; ?>
    <form class="basicForm" action="login" method="post">
        <label for="username">Username:
            <input type="text" name="username" required />
        </label>
        <label for="password">Password:
            <input type="password" name="password" required />
        </label>
        <input type="submit" value="Submit" />
    </form>
    <div class="flexColumn errorList">
        <?php
        if ($model->incorrectLoginAttempt) {
            echo "<p class='margin0'>Incorrect username or password.</p>";
        }
        ?>
</body>