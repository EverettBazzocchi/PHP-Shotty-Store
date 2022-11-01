<?php 
require('./api.php');

logout();
?>


<!DOCTYPE html>
<html lang="en">

<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
        <main id="home">
            <?php if (isset($_SESSION['user'])): ?>
            <h1>Welcome,
                <?php echo $_SESSION['user']['email']; ?>
            </h1>
            <a href="./functions/logout.php">Logout</a>
            <?php else: ?>
            <h1>Welcome, Guest</h1>
            <a href="./login.php">Login</a>
            <?php endif; ?>
        </main>
</body>

</html>