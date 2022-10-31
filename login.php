<?php
require('./functions/api.php');

if (isset($_POST['usernameRegister']) && isset($_POST['password'])) {
    try {
        createAccount($_POST['usernameRegister'], $_POST['password']);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else if (isset($_POST['usernameLogin']) && isset($_POST['password'])) {
    try {
        login($_POST['usernameLogin'], $_POST['password']);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if (isset($_SESSION['user'])) {
    echo $_SESSION['user']['email'];
}

?>

<!DOCTYPE html>
<html lang="en">


<?php require('functions/head.php') ?>

<body>
    <?= require('functions/header.php') ?>
        <main>

            <form id="loginForm" action="./login.php" method="post">
                <label for="usernameLogin">Username</label>
                <input type="text" name="usernameLogin" id="username" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <input type="submit" value="Login">
            </form>


            <form id="registerForm" action="./login.php" method="post">
                <label for="usernameRegister">Username</label>
                <input type="text" name="usernameRegister" id="username" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <input type="submit" value="Login">
            </form>
        </main>

</body>

</html>