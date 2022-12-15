<?php

require('./api.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    header('Location: ./');
}

try {
    verifyEmail($token);
} catch (Exception $e) {
    $error = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/project/functions/head.php') ?>
    <body>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/project/functions/header.php') ?>
        <main id="please-verify-page">
            <div class="container">
                <?php if (isset($error)) : ?>
                    <div class="error">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <h1>Thank You For Verifying Your Email</h1>
                <p>You can now log in to your account.</p>
                <a href="../login.php">Log In</a>
            </div>
        </main>
    </body>
</html>