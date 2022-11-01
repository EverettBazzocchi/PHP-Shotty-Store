<?php
require('functions/api.php');

?>

<!DOCTYPE html>
<html lang="en">

<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="home">
        <div class="store-header">
            <h2 class="store-title">Store</h2>
            <a class="cart-link" href="./shopping-cart.php">Shopping Cart</a>
        </div>
        <div class="container">
            <a href="./products.php?cat=ranks">
                <div class="item category">
                    <div class="blur">
                        <h3>Ranks</h3>
                    </div>
                </div>
            </a>
            <a href="./products.php?cat=items">
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