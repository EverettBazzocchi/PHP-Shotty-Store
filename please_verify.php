<?php 

require("./functions/api.php");

if (isset($_GET['email'])) {
    try {
        sendVerifyEmail($_GET['email']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    header('Location: /project/login.php');
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
                <h1>Please Verify Your Email (<?= $_GET['email']?>)</h1>
                <p>We have sent you an email to verify your account. Please check your email and click the link to verify your account.</p>
                <p>If you did not receive an email, please check your spam folder. If you still cannot find the email, please click the button below to resend the email.</p>
                <form action="./please_verify.php" method="post">
                    <input disabled hidden value="<?= $_GET['email']?>" name="email" />
                    <button type="submit">Resend Email</button>
                </form>
            </div>
        </main>
    </body>
</html>