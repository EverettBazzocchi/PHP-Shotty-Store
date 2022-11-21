<?php
require('functions/api.php');
?>

<!DOCTYPE html>
<html lang="en">

<?php require($_SERVER['DOCUMENT_ROOT'] . '/project/functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="home">
        <?php require('functions/store_header.php') ?>
        <div class="container">
            <a href="./products/ranks">
                <div class="item category">
                    <div class="blur">
                        <h3>Ranks</h3>
                    </div>
                </div>
            </a>
            <a href="./products/items">
                <div class="item category">
                    <div class="blur">
                        <h3>Items</h3>
                    </div>
                </div>
            </a>
        </div>
    </main>
</body>

</html>