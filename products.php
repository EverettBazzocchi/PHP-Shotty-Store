<?php

require('./functions/api.php');

if (isset($_GET['cat'])) {
    $category = $_GET['cat'];
} else {
    $category = "all";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="products-page">
        <div class="store-header">
            <h2 class="store-title">Store</h2>
            <a class="cart-link" href="./shopping-cart.php">Shopping Cart</a>
        </div>
        <div id="products-wrapper">
            <?php for ($i = 0; $i < 5; $i++) { ?>
            <?php foreach (getProducts($category) as $product): ?>
            <a href="product.php?id=<?= $product['id'] ?>">
                <div class="product-item" style="background-image: url(./uploads/images/<?= $product['image'] ?>)">

                    <div class="blur">

                        <h3 class="product-item-title">
                            <?= $product['name'] ?>
                        </h3>
                        <p class="product-item-price">
                            <?= $product['price'] ?>
                        </p>

                    </div>
                </div>
            </a>
        <?php endforeach; ?>
        <?php } ?>

        </div>

    </main>
</body>

</html>