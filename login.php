<?php
require('./functions/api.php');
$login_error = ""; 
$register_error = "";

if (isset($_POST['usernameRegister']) && isset($_POST['password'])) {
    try {
        if (!($_POST['passwordConfirm'] === $_POST['password'])) {
            throw new Exception('Passwords do not match');
        }
        createAccount($_POST['usernameRegister'], $_POST['password']);

    } catch (Exception $e) {
        $register_error = $e->getMessage();
    }
} else if (isset($_POST['usernameLogin']) && isset($_POST['password'])) {
    try {
        login($_POST['usernameLogin'], $_POST['password']);

    } catch (Exception $e) {
        $login_error = $e->getMessage();
    }
}



function email(string $form) : string {
    $email = "";
    $type = "";

    if(isset($_POST['usernameRegister'])) {
        $email = $_POST['usernameRegister'];
        $type = "register";
    }
    else if(isset($_POST['usernameLogin'])) {
        $email = $_POST['usernameLogin'];
        $type = "login";
    } 

    if($form == $type) {
        return $email;
    }
    else {
        return "";
    }
}

?>

<!DOCTYPE html>
<html lang="en">


<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="login-page">

        <?php require('functions/store_header.php') ?>
        
            <div class="forms" >
        <form id="login-form" action="./login.php" method="post">

            <h3>Login</h3>
        <?php if ($login_error): ?>
        <h6 class="error">
            <?= $login_error ?>
        </h6>
        <?php endif; ?>
            <label for="usernameLogin">Email</label>
            <input type="text" name="usernameLogin" value="<?= email("login")?>" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Login">
        </form>


        <form id="register-form" action="./login.php" method="post">
            <h3>Register</h3>
            
        <?php if ($register_error): ?>
        <h6 class="error">
            <?= $register_error ?>
        </h6>
        <?php endif; ?>
            <label for="usernameRegister">Email</label>
            <input type="text" name="usernameRegister" value="<?= email("register")?>" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <label for="passwordConfirm">Confirm Password</label>
            <input type="password" name="passwordConfirm" id="passwordConfirm" required>
            <input type="submit" value="Register">
        </form></div>
    </main>

</body>

</html>